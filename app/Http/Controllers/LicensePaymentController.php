<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\LicensePayment;
use App\Models\LicensePaymentItem;
use App\Models\User;
use App\Notifications\PaymentCreatedNotification;
use App\Notifications\PaymentCompletedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\PaymentIntent;

class LicensePaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Show payment details for a license
     */
    public function show(License $license)
    {
        $this->authorizeAccess($license);

        $payment = $license->activePayment ?? $license->latestPayment;
        
        // Get all payments for history (excluding current active one)
        $paymentHistory = $license->payments()
            ->with(['items', 'creator', 'payer'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('files.license-payment', compact('license', 'payment', 'paymentHistory'));
    }

    /**
     * Create payment form (Agent/Admin)
     */
    public function create(License $license)
    {
        $this->authorizeAdminAgent();

        // Check if billing is open before allowing payment creation
        if (!$license->canCreatePayment()) {
            return redirect()
                ->route('admin.licenses.show', $license)
                ->with('error', 'Cannot create payment. Renewal status: ' . $license->renewal_status_label . ', Billing status: ' . $license->billing_status_label);
        }

        return view('files.license-payment-create', compact('license'));
    }

    /**
     * Store a new payment with items (Agent/Admin)
     */
    public function store(Request $request, License $license)
    {
        $this->authorizeAdminAgent();

        // Check if billing is open before allowing payment creation
        if (!$license->canCreatePayment()) {
            return redirect()
                ->route('admin.licenses.show', $license)
                ->with('error', 'Cannot create payment. Billing is not open.');
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.label' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        // Create payment
        $payment = $license->payments()->create([
            'created_by' => Auth::id(),
            'assigned_agent_id' => Auth::id(), // Assign the agent who created this payment
            'status' => LicensePayment::STATUS_DRAFT,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Add items
        foreach ($validated['items'] as $index => $item) {
            $payment->items()->create([
                'label' => $item['label'],
                'description' => $item['description'] ?? null,
                'amount' => $item['amount'],
                'sort_order' => $index,
            ]);
        }

        // Recalculate and open for payment
        $payment->openForPayment();

        // Update billing status to open (payment created), then to invoiced (ready for client to pay)
        $license->markBillingOpen();
        $license->markBillingInvoiced();

        // Notify client
        $license->client->notify(new PaymentCreatedNotification($license, $payment));

        return redirect()
            ->route('admin.licenses.payments.show', $license)
            ->with('success', 'Payment created and client has been notified.');
    }

    /**
     * Add item to existing payment (Agent/Admin)
     */
    public function addItem(Request $request, License $license, LicensePayment $payment)
    {
        $this->authorizeAdminAgent();

        if (!$payment->isDraft() && !$payment->isOpen()) {
            return back()->with('error', 'Cannot modify a completed payment.');
        }

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $payment->items()->create([
            'label' => $validated['label'],
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'sort_order' => $payment->items()->count(),
        ]);
        
        // Assign the current agent/admin if payment has no assigned agent yet
        if (empty($payment->assigned_agent_id)) {
            $payment->update(['assigned_agent_id' => Auth::id()]);
        }

        return back()->with('success', 'Item added successfully.');
    }

    /**
     * Remove item from payment (Agent/Admin)
     */
    public function removeItem(License $license, LicensePayment $payment, LicensePaymentItem $item)
    {
        $this->authorizeAdminAgent();

        if (!$payment->isDraft() && !$payment->isOpen()) {
            return back()->with('error', 'Cannot modify a completed payment.');
        }

        $item->delete();
        
        // Assign the current agent/admin if payment has no assigned agent yet
        if (empty($payment->assigned_agent_id)) {
            $payment->update(['assigned_agent_id' => Auth::id()]);
        }

        return back()->with('success', 'Item removed successfully.');
    }

    /**
     * Stripe Checkout - Create checkout session (Client pays online)
     */
    public function checkout(License $license, LicensePayment $payment)
    {
        // Only client or admin can initiate checkout
        $role = Auth::user()->Role->name;
        if ($role === 'Client' && $license->client_id !== Auth::id()) {
            abort(403);
        }

        if (!$payment->isOpen()) {
            return back()->with('error', 'This payment is not available for checkout.');
        }

        // Create line items for Stripe
        $lineItems = [];
        foreach ($payment->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->label,
                        'description' => $item->description ?? 'License fee',
                    ],
                    'unit_amount' => (int)($item->amount * 100), // Convert to cents
                ],
                'quantity' => 1,
            ];
        }

        // Create Stripe checkout session
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('admin.licenses.payments.success', [
                'license' => $license->id,
                'payment' => $payment->id,
            ]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('admin.licenses.payments.cancel', [
                'license' => $license->id,
                'payment' => $payment->id,
            ]),
            'customer_email' => $license->email,
            'metadata' => [
                'license_id' => $license->id,
                'payment_id' => $payment->id,
            ],
        ]);

        // Store session ID
        $payment->update(['stripe_checkout_session_id' => $session->id]);

        return redirect($session->url);
    }

    /**
     * Handle successful Stripe payment
     */
    public function success(Request $request, License $license, LicensePayment $payment)
    {
        $sessionId = $request->get('session_id');

        if ($sessionId && $payment->stripe_checkout_session_id === $sessionId) {
            // Retrieve session to get payment intent
            $session = StripeSession::retrieve($sessionId);

            $payment->markAsPaid(
                LicensePayment::METHOD_ONLINE,
                Auth::id(),
                $session->payment_intent
            );

            // Update license billing status to paid
            $license->markBillingPaid();

            // Notify client
            $license->client->notify(new PaymentCompletedNotification($license, $payment));

            // Notify admins and assigned agent about payment received
            $this->notifyAdminsAndAgent($license, $payment);

            return redirect()
                ->route('admin.licenses.show', $license)
                ->with('success', 'Payment completed successfully!');
        }

        return redirect()
            ->route('admin.licenses.payments.show', $license)
            ->with('error', 'Payment verification failed.');
    }

    /**
     * Handle cancelled Stripe payment
     */
    public function cancel(License $license, LicensePayment $payment)
    {
        return redirect()
            ->route('admin.licenses.payments.show', $license)
            ->with('info', 'Payment was cancelled. You can try again when ready.');
    }

    /**
     * Process offline/over-the-counter payment (Agent/Admin) - POS Style
     */
    public function payOffline(Request $request, License $license, LicensePayment $payment)
    {
        $this->authorizeAdminAgent();

        if (!$payment->isOpen()) {
            return back()->with('error', 'This payment is not available.');
        }

        $validated = $request->validate([
            'amount_received' => 'required|numeric|min:' . $payment->total_amount,
            'notes' => 'nullable|string',
        ]);

        // Calculate change
        $amountReceived = floatval($validated['amount_received']);
        $totalDue = floatval($payment->total_amount);
        $change = $amountReceived - $totalDue;

        // Build POS receipt note
        $posNote = "=== POS TRANSACTION ===\n";
        $posNote .= "Date: " . now()->format('M d, Y h:i A') . "\n";
        $posNote .= "Cashier: " . Auth::user()->name . "\n";
        $posNote .= "------------------------\n";
        $posNote .= "Total Due: $" . number_format($totalDue, 2) . "\n";
        $posNote .= "Amount Received: $" . number_format($amountReceived, 2) . "\n";
        $posNote .= "Change Given: $" . number_format($change, 2) . "\n";
        $posNote .= "------------------------";
        
        if ($validated['notes']) {
            $posNote .= "\nNotes: " . $validated['notes'];
        }

        $payment->markAsPaid(
            LicensePayment::METHOD_OFFLINE,
            Auth::id()
        );

        // Update notes with POS transaction details
        $existingNotes = $payment->notes ? $payment->notes . "\n\n" : "";
        $payment->update(['notes' => $existingNotes . $posNote]);

        // Update license billing status to paid
        $license->markBillingPaid();

        // Notify client
        $license->client->notify(new PaymentCompletedNotification($license, $payment));

        // Notify admins and assigned agent about payment received
        $this->notifyAdminsAndAgent($license, $payment);

        return redirect()
            ->route('admin.licenses.show', $license)
            ->with('success', 'Payment completed! Change: $' . number_format($change, 2));
    }

    /**
     * Override payment (Agent/Admin) - Mark as paid without actual payment
     */
    public function override(Request $request, License $license, LicensePayment $payment)
    {
        $this->authorizeAdminAgent();

        $validated = $request->validate([
            'override_reason' => 'required|string|max:1000',
        ]);

        $payment->override(Auth::id(), $validated['override_reason']);

        // Update license billing status to overridden
        $license->markBillingOverridden();

        // Notify client
        $license->client->notify(new PaymentCompletedNotification($license, $payment));

        // Notify admins and assigned agent about payment override
        $this->notifyAdminsAndAgent($license, $payment);

        return redirect()
            ->route('admin.licenses.show', $license)
            ->with('success', 'Payment overridden successfully.');
    }

    /**
     * Cancel payment (Agent/Admin)
     */
    public function destroy(License $license, LicensePayment $payment)
    {
        $this->authorizeAdminAgent();

        if ($payment->isPaid() || $payment->isOverridden()) {
            return back()->with('error', 'Cannot cancel a completed payment.');
        }

        $payment->cancel();

        return redirect()
            ->route('admin.licenses.show', $license)
            ->with('success', 'Payment cancelled.');
    }

    /**
     * Check if current user is admin or agent
     */
    private function authorizeAdminAgent(): void
    {
        $role = Auth::user()->Role->name;
        if (!in_array($role, ['Admin', 'Agent'])) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Check if user can access this license
     */
    private function authorizeAccess(License $license): void
    {
        $role = Auth::user()->Role->name;
        if ($role === 'Client' && $license->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Notify all admins and the assigned agent about payment completion
     */
    private function notifyAdminsAndAgent(License $license, LicensePayment $payment): void
    {
        // Get all admins
        $admins = User::whereHas('Role', function($query) {
            $query->where('name', 'Admin');
        })->get();

        // Notify all admins
        foreach ($admins as $admin) {
            // Don't notify the current user if they are an admin (they already know)
            if ($admin->id !== Auth::id()) {
                $admin->notify(new PaymentCompletedNotification($license, $payment));
            }
        }

        // Notify assigned agent if exists and is not the current user
        if ($license->assigned_agent_id && $license->assigned_agent_id !== Auth::id()) {
            $agent = $license->assignedAgent;
            if ($agent) {
                $agent->notify(new PaymentCompletedNotification($license, $payment));
            }
        }
    }
}
