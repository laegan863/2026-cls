<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\LicensePayment;
use App\Models\LicensePaymentItem;
use App\Models\LicenseRenewal;
use App\Models\User;
use App\Notifications\LicenseCreatedNotification;
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

        // If no items, create a single line item with total amount
        if (empty($lineItems) && $payment->total_amount > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'License/Permit Fee',
                        'description' => 'License renewal fee for ' . ($license->permit_type ?? 'License'),
                    ],
                    'unit_amount' => (int)($payment->total_amount * 100),
                ],
                'quantity' => 1,
            ];
        }

        if (empty($lineItems)) {
            return back()->with('error', 'No payment items found. Please contact support.');
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

            // Determine if this is a new enrollment or renewal
            // New enrollment = no previous PAID payments for this license
            $previousPaidPayments = $license->payments()
                ->where('id', '!=', $payment->id)
                ->whereIn('status', ['paid', 'overridden'])
                ->count();
            
            $isNewEnrollment = $previousPaidPayments === 0;
            
            if ($isNewEnrollment) {
                // New enrollment - set initial expiration date directly
                $this->setInitialExpirationDate($license);
                $successMessage = 'Payment completed successfully! License has been activated.';
            } else {
                // Renewal - create renewal record and wait for file upload
                $this->createRenewalRecord($license, $payment);
                $successMessage = 'Payment completed successfully! Please upload the renewal evidence file to complete the renewal process.';
            }

            // Notify client
            $license->client->notify(new PaymentCompletedNotification($license, $payment));

            // Notify admins and assigned agent about payment received
            $this->notifyAdminsAndAgent($license, $payment);

            return redirect()
                ->route('admin.licenses.show', $license)
                ->with('success', $successMessage);
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

        // Determine if this is a new enrollment or renewal
        // New enrollment = no previous PAID payments for this license
        $previousPaidPayments = $license->payments()
            ->where('id', '!=', $payment->id)
            ->whereIn('status', ['paid', 'overridden'])
            ->count();
        
        $isNewEnrollment = $previousPaidPayments === 0;
        
        if ($isNewEnrollment) {
            // New enrollment - set initial expiration date directly
            $this->setInitialExpirationDate($license);
            $successMessage = 'Payment completed! Change: $' . number_format($change, 2) . '. License has been activated.';
        } else {
            // Renewal - create renewal record and wait for file upload
            $this->createRenewalRecord($license, $payment);
            $successMessage = 'Payment completed! Change: $' . number_format($change, 2) . '. Please upload the renewal evidence file.';
        }

        // Notify client
        $license->client->notify(new PaymentCompletedNotification($license, $payment));

        // Notify admins and assigned agent about payment received
        $this->notifyAdminsAndAgent($license, $payment);

        return redirect()
            ->route('admin.licenses.show', $license)
            ->with('success', $successMessage);
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

        // Determine if this is a new enrollment or renewal
        // New enrollment = no previous PAID payments for this license
        $previousPaidPayments = $license->payments()
            ->where('id', '!=', $payment->id)
            ->whereIn('status', ['paid', 'overridden'])
            ->count();
        
        $isNewEnrollment = $previousPaidPayments === 0;
        
        if ($isNewEnrollment) {
            // New enrollment - set initial expiration date directly
            $this->setInitialExpirationDate($license);
            $successMessage = 'Payment overridden successfully. License has been activated.';
        } else {
            // Renewal - create renewal record and wait for file upload
            $this->createRenewalRecord($license, $payment);
            $successMessage = 'Payment overridden successfully. Please upload the renewal evidence file.';
        }

        // Notify client
        $license->client->notify(new PaymentCompletedNotification($license, $payment));

        // Notify admins and assigned agent about payment override
        $this->notifyAdminsAndAgent($license, $payment);

        return redirect()
            ->route('admin.licenses.show', $license)
            ->with('success', $successMessage);
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
     * Notify all admins and agents about payment completion
     */
    private function notifyAdminsAndAgent(License $license, LicensePayment $payment): void
    {
        // Get all admins and agents
        $staffUsers = User::whereHas('Role', function($query) {
            $query->whereIn('name', ['Admin', 'Agent']);
        })->where('id', '!=', Auth::id())->get();

        // Notify all admins and agents (except the current user)
        foreach ($staffUsers as $user) {
            $user->notify(new PaymentCompletedNotification($license, $payment));
        }
    }

    /**
     * Extend license expiration date based on permit type renewal cycle
     */
    private function extendExpirationDate(License $license): void
    {
        $permitType = $license->permitType;
        
        if (!$permitType || !$permitType->has_renewal || !$permitType->renewal_cycle_months) {
            return;
        }

        $renewalMonths = $permitType->renewal_cycle_months;
        
        // If license has existing expiration date, extend from that date
        // Otherwise, extend from today
        $baseDate = $license->expiration_date && $license->expiration_date->isFuture() 
            ? $license->expiration_date 
            : now();
        
        $newExpirationDate = $baseDate->copy()->addMonths($renewalMonths);
        
        // Calculate renewal window open date as 2 months before expiration
        $renewalWindowOpenDate = $newExpirationDate->copy()->subMonths(2);
        
        $license->update([
            'expiration_date' => $newExpirationDate,
            'renewal_window_open_date' => $renewalWindowOpenDate,
            'renewal_status' => 'closed', // Reset renewal status for new period
        ]);
    }

    /**
     * Set initial expiration date for new enrollments (first-time license creation)
     */
    private function setInitialExpirationDate(License $license): void
    {
        $permitType = $license->permitType;
        
        if (!$permitType || !$permitType->has_renewal || !$permitType->renewal_cycle_months) {
            return;
        }

        $renewalMonths = $permitType->renewal_cycle_months;
        
        // For new enrollments, start from today
        $newExpirationDate = now()->addMonths($renewalMonths);
        
        // Calculate renewal window open date as 2 months before expiration
        $renewalWindowOpenDate = $newExpirationDate->copy()->subMonths(2);
        
        $license->update([
            'expiration_date' => $newExpirationDate,
            'renewal_window_open_date' => $renewalWindowOpenDate,
            'renewal_status' => 'closed',
        ]);

        // Notify client about license activation
        $license->client->notify(new LicenseCreatedNotification($license));
    }

    /**
     * Create a renewal record when a renewal payment is completed
     */
    private function createRenewalRecord(License $license, LicensePayment $payment): void
    {
        $permitType = $license->permitType;
        
        // Count existing renewals to determine renewal number
        $renewalNumber = $license->renewals()->count() + 1;
        
        // Calculate what the new expiration date will be after file is uploaded
        $renewalMonths = $permitType && $permitType->renewal_cycle_months 
            ? $permitType->renewal_cycle_months 
            : 12;
        
        $baseDate = $license->expiration_date && $license->expiration_date->isFuture() 
            ? $license->expiration_date 
            : now();
        
        $newExpirationDate = $baseDate->copy()->addMonths($renewalMonths);
        
        // Create the renewal record
        LicenseRenewal::create([
            'license_id' => $license->id,
            'payment_id' => $payment->id,
            'renewal_number' => $renewalNumber,
            'previous_expiration_date' => $license->expiration_date,
            'new_expiration_date' => $newExpirationDate,
            'status' => LicenseRenewal::STATUS_PENDING_FILE,
        ]);
    }
}
