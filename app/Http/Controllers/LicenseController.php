<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\LicenseRenewal;
use App\Models\User;
use App\Models\LicenseRequirement;
use App\Notifications\LicenseCreatedNotification;
use App\Notifications\LicenseStatusNotification;
use App\Notifications\LicenseRenewedNotification;
use App\Notifications\RenewalStatusNotification;
use App\Notifications\BillingStatusNotification;
use App\Mail\LicenseRenewedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LicenseController extends Controller
{
    /**
     * Display a listing of the licenses.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Check permission - user needs 'view' permission for Licensing module
        if (!$user->hasPermission('view')) {
            abort(403, 'Unauthorized access.');
        }
        
        $role = $user->Role->name;
        
        // Build query
        $query = License::with(['client', 'latestPayment.assignedAgent']);
        
        // Admin and Agent can see all licenses, Client can only see their own
        if ($role !== 'Admin' && $role !== 'Agent') {
            // Client - only show their own licenses
            $query->where('client_id', Auth::id());
        }
        
        // Apply filters
        if ($request->filled('store_name')) {
            $query->where('store_name', 'like', '%' . $request->store_name . '%');
        }
        
        if ($request->filled('client_id') && ($role === 'Admin' || $role === 'Agent')) {
            $query->where('client_id', $request->client_id);
        }
        
        if ($request->filled('renewal_status')) {
            $query->where('renewal_status', $request->renewal_status);
        }
        
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        $licenses = $query->latest()->get();
        
        return view('files.licensing', compact('licenses'));
    }

    /**
     * Display a listing of upcoming renewals.
     */
    public function upcomingRenewals(Request $request)
    {
        $user = Auth::user();
        
        // Check permission - user needs 'view' permission for Licensing module
        if (!$user->hasPermission('view')) {
            abort(403, 'Unauthorized access.');
        }
        
        $role = $user->Role->name;
        
        // Build query for all renewals - prioritize those expiring within 2 months
        // Uses CASE to sort: renewable stores (within 2 months) first, then others
        $twoMonthsFromNow = now()->addMonths(2)->format('Y-m-d');
        $query = License::with(['client', 'latestPayment.assignedAgent'])
            ->where('is_active', true)
            ->whereNotNull('expiration_date');
        
        // Admin and Agent can see all, Client can only see their own
        if ($role !== 'Admin' && $role !== 'Agent') {
            $query->where('client_id', Auth::id());
        }
        
        // Apply filters
        if ($request->filled('store_name')) {
            $query->where('store_name', 'like', '%' . $request->store_name . '%');
        }
        
        if ($request->filled('client_id') && ($role === 'Admin' || $role === 'Agent')) {
            $query->where('client_id', $request->client_id);
        }
        
        if ($request->filled('renewal_status')) {
            $query->where('renewal_status', $request->renewal_status);
        }
        
        // Order by: renewable stores first (expiring within 2 months), then by expiration date
        $upcomingRenewals = $query
            ->orderByRaw("CASE WHEN expiration_date <= ? THEN 0 ELSE 1 END", [$twoMonthsFromNow])
            ->orderBy('expiration_date', 'asc')
            ->get();
        
        return view('files.upcoming-renewals', compact('upcomingRenewals'));
    }

    /**
     * Show the form for creating a new license.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Check permission - user needs 'create' permission for Licensing module
        if (!$user->hasPermission('create')) {
            abort(403, 'Unauthorized access.');
        }
        
        // Get agents (Admin and Agent roles) for assignment dropdown
        $agents = User::whereHas('Role', function ($query) {
            $query->whereIn('name', ['Agent', 'Admin']);
        })->orderBy('name')->get();
        
        return view('files.add-new-license-user', compact('agents'));
    }

    /**
     * Store a newly created license in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check permission - user needs 'create' permission for Licensing module
        if (!$user->hasPermission('create')) {
            abort(403, 'Unauthorized access.');
        }
        
        $role = $user->Role->name;
        
        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'client_name' => 'nullable|string',
            'email' => 'nullable|email',
            'primary_contact_info' => 'nullable|string',
            'legal_name' => 'nullable|string',
            'dba' => 'nullable|string',
            'fein' => 'nullable|string|max:9',
            'sales_tax_id' => 'nullable|string',
            'street_number' => 'nullable|string',
            'street_name' => 'nullable|string',
            'city' => 'nullable|string',
            'county' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'permit_type' => 'required|string',
            'permit_subtype' => 'nullable|string',
            'jurisdiction_level' => 'nullable|string',
            'agency_name' => 'nullable|string',
            'permit_number' => 'nullable|string',
            'payment_action' => 'nullable|in:stripe,override',
            // Requirements validation
            'requirements' => 'nullable|array',
            'requirements.*.label' => 'nullable|string|max:255',
            'requirements.*.description' => 'nullable|string',
            'requirements.*.value' => 'nullable|string',
            'requirement_files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240',
        ]);

        // Get client name
        if ($role === 'Admin') {
            $client = User::find($request->input('client_id'));
            $validated['client_name'] = $client ? $client->name : null;
        } else {
            $validated['client_id'] = Auth::id();
            $validated['client_name'] = Auth::user()->name;
        }

        // Build store address from components
        $storeAddress = trim(($request->street_number ?? '') . ' ' . ($request->street_name ?? ''));
        $validated['store_name'] = $validated['legal_name'] ?? null;
        $validated['store_address'] = $storeAddress;
        $validated['store_city'] = $validated['city'] ?? null;
        $validated['store_state'] = $validated['state'] ?? null;
        $validated['store_zip_code'] = $validated['zip_code'] ?? null;
        
        // Note: expiration_date is NOT set here for new enrollments
        // It will be set after payment is completed in LicensePaymentController

        // Generate permit number if not provided
        if (empty($validated['permit_number'])) {
            $validated['permit_number'] = 'PN-' . strtoupper(Str::random(8));
        }
        $validated['submission_confirmation_number'] = $validated['permit_number'];

        $paymentAction = $request->input('payment_action', 'stripe');

        // Remove fields that don't go in licenses table
        unset($validated['requirements']);
        unset($validated['requirement_files']);
        unset($validated['payment_action']);
        unset($validated['street_number']);
        unset($validated['street_name']);
        unset($validated['county']);
        unset($validated['sales_tax_id']);
        
        $validated['transaction_id'] = Str::random(12);
        $license = License::create($validated);

        // Update renewal and billing status based on expiration date
        // - If within 2 months: renewal = open, billing = pending
        // - If more than 2 months: renewal = closed, billing = closed
        $license->updateRenewalBillingStatus();

        // Handle requirements if any
        if ($request->has('requirements')) {
            $requirements = $request->input('requirements');
            $requirementFiles = $request->file('requirement_files', []);
            
            foreach ($requirements as $index => $requirement) {
                // Skip empty requirements (no label)
                if (empty($requirement['label'])) {
                    continue;
                }
                
                $filePath = null;
                
                // Handle file upload if exists
                if (isset($requirementFiles[$index]) && $requirementFiles[$index]->isValid()) {
                    $file = $requirementFiles[$index];
                    $fileName = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('requirements/' . $license->id, $fileName, 'public');
                }
                
                // Determine status based on whether value is provided
                $status = !empty($requirement['value']) ? LicenseRequirement::STATUS_SUBMITTED : LicenseRequirement::STATUS_PENDING;
                
                LicenseRequirement::create([
                    'license_id' => $license->id,
                    'created_by' => Auth::id(),
                    'label' => $requirement['label'],
                    'description' => $requirement['description'] ?? null,
                    'value' => $requirement['value'] ?? null,
                    'file_path' => $filePath,
                    'status' => $status,
                    'submitted_at' => !empty($requirement['value']) ? now() : null,
                ]);
            }
        }

        // Send notification to admins and assigned agent about new license
        $this->notifyLicenseCreated($license);

        // Handle payment action
        if ($paymentAction === 'override' && $role === 'Admin') {
            // Admin overrides payment - mark as paid
            $license->billing_status = 'paid';
            $license->save();
            
            // Create a payment record with override status
            $totalAmount = $this->calculatePermitFees($permitType);
            
            if ($totalAmount > 0) {
                $payment = \App\Models\LicensePayment::create([
                    'license_id' => $license->id,
                    'created_by' => Auth::id(),
                    'assigned_agent_id' => Auth::id(),
                    'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                    'total_amount' => $totalAmount,
                    'status' => 'overridden',
                    'payment_method' => 'override',
                    'paid_at' => now(),
                    'notes' => 'Payment overridden by Admin',
                ]);
                
                // Add payment items from permit type fees
                $this->addPermitFeesToPayment($payment, $permitType);
            }
            
            // Extend expiration date based on permit type renewal cycle
            $this->extendExpirationDate($license, $permitType);
            
            return redirect()->route('admin.licenses.show', $license)->with('success', 'License created and marked as paid!');
        } else {
            // Create payment record and redirect to payment page
            $totalAmount = $this->calculatePermitFees($permitType);
            
            if ($totalAmount > 0) {
                $payment = \App\Models\LicensePayment::create([
                    'license_id' => $license->id,
                    'created_by' => Auth::id(),
                    'assigned_agent_id' => null,
                    'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                    'total_amount' => $totalAmount,
                    'status' => 'open',
                ]);
                
                // Add payment items from permit type fees
                $this->addPermitFeesToPayment($payment, $permitType);
                
                // Redirect to Stripe checkout
                return redirect()->route('admin.licenses.payments.checkout', [$license, $payment]);
            }
            
            return redirect()->route('admin.licenses.show', $license)->with('success', 'License created successfully!');
        }
    }

    /**
     * Calculate total fees from permit type
     */
    private function calculatePermitFees($permitType)
    {
        if (!$permitType) return 0;
        
        return ($permitType->government_fee ?? 0) +
               ($permitType->cls_service_fee ?? 0) +
               ($permitType->city_county_fee ?? 0) +
               ($permitType->additional_fee ?? 0);
    }

    /**
     * Add permit type fees as payment items
     */
    private function addPermitFeesToPayment($payment, $permitType)
    {
        if (!$permitType) return;
        
        if ($permitType->government_fee > 0) {
            \App\Models\LicensePaymentItem::create([
                'license_payment_id' => $payment->id,
                'label' => 'Government Fee',
                'description' => 'Government processing fee',
                'amount' => $permitType->government_fee,
            ]);
        }
        
        if ($permitType->cls_service_fee > 0) {
            \App\Models\LicensePaymentItem::create([
                'license_payment_id' => $payment->id,
                'label' => 'CLS-360 Service Fee',
                'description' => 'CLS-360 service and processing fee',
                'amount' => $permitType->cls_service_fee,
            ]);
        }
        
        if ($permitType->city_county_fee > 0) {
            \App\Models\LicensePaymentItem::create([
                'license_payment_id' => $payment->id,
                'label' => 'City/County Fee',
                'description' => 'Local jurisdiction fee',
                'amount' => $permitType->city_county_fee,
            ]);
        }
        
        if ($permitType->additional_fee > 0) {
            \App\Models\LicensePaymentItem::create([
                'license_payment_id' => $payment->id,
                'label' => 'Additional Fee',
                'description' => $permitType->additional_fee_description ?? 'Additional processing fee',
                'amount' => $permitType->additional_fee,
            ]);
        }
    }

    /**
     * Extend license expiration date based on permit type renewal cycle
     */
    private function extendExpirationDate($license, $permitType)
    {
        if (!$permitType || !$permitType->has_renewal || !$permitType->renewal_cycle_months) {
            return;
        }

        $renewalMonths = $permitType->renewal_cycle_months;
        
        // If license has existing expiration date in the future, extend from that date
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

    public function show(License $license)
    {
        $user = Auth::user();
        
        // Check permission - user needs 'view' permission for Licensing module
        if (!$user->hasPermission('view')) {
            abort(403, 'Unauthorized access.');
        }
        
        $role = $user->Role->name;
        
        if ($role === 'Client' && $license->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('files.license-show', compact('license'));
    }

    public function edit(License $license)
    {
        $user = Auth::user();
        
        // Check permission - user needs 'edit' permission for Licensing module
        if (!$user->hasPermission('edit')) {
            abort(403, 'Unauthorized access.');
        }
        
        $role = $user->Role->name;
        
        if ($role === 'Client' && $license->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('files.license-edit', compact('license'));
    }

    public function update(Request $request, License $license)
    {
        $user = Auth::user();
        
        // Check permission - user needs 'edit' permission for Licensing module
        if (!$user->hasPermission('edit')) {
            abort(403, 'Unauthorized access.');
        }
        
        $role = $user->Role->name;
        
        if ($role === 'Client' && $license->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        $validated = $request->validate([
            'email' => 'nullable|email',
            'primary_contact_info' => 'nullable|string',
            'legal_name' => 'nullable|string',
            'dba' => 'nullable|string',
            'fein' => 'nullable|string',
            'store_name' => 'nullable|string',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string',
            'store_email' => 'nullable|email',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'permit_type' => 'nullable|string',
            'permit_subtype' => 'nullable|string',
            'jurisdiction_country' => 'nullable|string',
            'jurisdiction_state' => 'nullable|string',
            'jurisdiction_city' => 'nullable|string',
            'jurisdiction_federal' => 'nullable|string',
            'agency_name' => 'nullable|string',
            'expiration_date' => 'nullable|date',
            'submission_confirmation_number' => 'nullable|string',
        ]);

        $license->update($validated);
        return redirect()->route('admin.licenses.index')->with('success', 'License updated successfully!');
    }

    public function destroy(License $license)
    {
        $user = Auth::user();
        
        // Check permission - user needs 'delete' permission for Licensing module
        if (!$user->hasPermission('delete')) {
            abort(403, 'Unauthorized access.');
        }
        
        $role = $user->Role->name;
        
        if ($role === 'Client' && $license->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        $license->delete();

        return redirect()->route('admin.licenses.index')->with('success', 'License deleted successfully!');
    }

    /**
     * Upload license document (Admin/Agent only)
     */
    public function uploadDocument(Request $request, License $license)
    {
        $user = Auth::user();
        $role = $user->Role->name;
        
        // Only Admin/Agent can upload documents
        if (!in_array($role, ['Admin', 'Agent'])) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only allow upload when billing is paid or overridden
        if (!in_array($license->billing_status, ['paid', 'overridden'])) {
            return back()->with('error', 'Cannot upload document. Payment must be completed first.');
        }
        
        $validated = $request->validate([
            'license_document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
        ]);
        
        // Delete old document if exists
        if ($license->license_document) {
            Storage::disk('public')->delete($license->license_document);
        }
        
        // Store the new document
        $file = $request->file('license_document');
        $originalName = $file->getClientOriginalName();
        $path = $file->store('license-documents/' . $license->id, 'public');
        
        $license->update([
            'license_document' => $path,
            'license_document_name' => $originalName,
            'license_document_uploaded_at' => now(),
            'license_document_uploaded_by' => Auth::id(),
        ]);
        
        return back()->with('success', 'License document uploaded successfully! The client can now view and download it.');
    }

    /**
     * Delete license document (Admin/Agent only)
     */
    public function deleteDocument(License $license)
    {
        $user = Auth::user();
        $role = $user->Role->name;
        
        // Only Admin/Agent can delete documents
        if (!in_array($role, ['Admin', 'Agent'])) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($license->license_document) {
            Storage::disk('public')->delete($license->license_document);
            
            $license->update([
                'license_document' => null,
                'license_document_name' => null,
                'license_document_uploaded_at' => null,
                'license_document_uploaded_by' => null,
            ]);
        }
        
        return back()->with('success', 'License document deleted successfully.');
    }

    /**
     * Upload renewal evidence file and complete the renewal process
     */
    public function uploadRenewalFile(Request $request, License $license, LicenseRenewal $renewal)
    {
        $user = Auth::user();
        $role = $user->Role->name;
        
        // Only Admin/Agent can upload renewal files
        if (!in_array($role, ['Admin', 'Agent'])) {
            abort(403, 'Unauthorized action.');
        }
        
        // Verify the renewal belongs to this license
        if ($renewal->license_id !== $license->id) {
            abort(404, 'Renewal not found for this license.');
        }
        
        // Check if renewal is pending file
        if ($renewal->status !== 'pending_file') {
            return back()->with('error', 'This renewal is not pending file upload.');
        }
        
        $validated = $request->validate([
            'renewal_evidence_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);
        
        // Delete old file if exists
        if ($renewal->renewal_evidence_file) {
            Storage::disk('public')->delete($renewal->renewal_evidence_file);
        }
        
        // Store the new file
        $file = $request->file('renewal_evidence_file');
        $path = $file->store('renewal-evidence/' . $license->id, 'public');
        
        // Update renewal record with file info
        $renewal->update([
            'renewal_evidence_file' => $path,
            'file_uploaded_at' => now(),
            'file_uploaded_by' => $user->id,
            'status' => 'completed',
        ]);
        
        // Now extend the expiration date
        $newExpirationDate = $renewal->new_expiration_date;
        $renewalWindowOpenDate = $newExpirationDate->copy()->subMonths(2);
        
        $license->update([
            'expiration_date' => $newExpirationDate,
            'renewal_window_open_date' => $renewalWindowOpenDate,
            'renewal_status' => 'closed',
        ]);
        
        // Send renewal notification to client
        $license->client->notify(new LicenseRenewedNotification(
            $license, 
            $newExpirationDate->format('M d, Y'),
            $path
        ));
        
        return back()->with('success', 'Renewal #' . $renewal->renewal_number . ' completed! License expiration extended to ' . $newExpirationDate->format('M d, Y') . '. Client has been notified.');
    }

    /**
     * Initiate renewal payment for a license
     */
    public function initiateRenewal(License $license)
    {
        $user = Auth::user();
        $role = $user->Role->name;
        
        // Check if renewal is open or expired
        if (!in_array($license->renewal_status, ['open', 'expired'])) {
            return back()->with('error', 'Renewal is not available for this license.');
        }
        
        // Check if there's already an active payment
        if ($license->activePayment) {
            return redirect()->route('admin.licenses.payments.show', $license)
                ->with('info', 'A payment already exists for this license.');
        }
        
        // Get permit type for fees
        $permitType = $license->permitType;
        if (!$permitType) {
            return back()->with('error', 'No permit type found for this license.');
        }
        
        // Calculate total fees
        $totalAmount = $this->calculatePermitFees($permitType);
        
        if ($totalAmount <= 0) {
            return back()->with('error', 'No fees configured for this permit type.');
        }
        
        // Create payment record
        $payment = \App\Models\LicensePayment::create([
            'license_id' => $license->id,
            'created_by' => Auth::id(),
            'assigned_agent_id' => $role === 'Client' ? null : Auth::id(),
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'total_amount' => $totalAmount,
            'status' => 'open',
        ]);
        
        // Add payment items from permit type fees
        $this->addPermitFeesToPayment($payment, $permitType);
        
        // Update billing status
        $license->markBillingInvoiced();
        
        // Redirect to checkout
        return redirect()->route('admin.licenses.payments.checkout', [$license, $payment]);
    }

    /**
     * Toggle the active status of a store/license.
     */
    public function toggleStatus(License $license)
    {
        $user = Auth::user();
        
        // Only Admin can toggle store status
        if ($user->Role->name !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }
        
        $license->is_active = !$license->is_active;
        $license->save();
        
        $status = $license->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.licenses.index')->with('success', "Store has been {$status} successfully!");
    }

    /**
     * Refresh renewal and billing status based on expiration date.
     */
    public function refreshStatus(License $license)
    {
        $user = Auth::user();
        
        // Only users with edit permission can refresh status
        if (!$user->hasPermission('edit')) {
            abort(403, 'Unauthorized action.');
        }

        $license->updateRenewalBillingStatus();
        
        return redirect()
            ->route('admin.licenses.show', $license)
            ->with('success', 'Renewal and billing status updated. Renewal: ' . $license->renewal_status_label . ', Billing: ' . $license->billing_status_label);
    }

    /**
     * Bulk refresh renewal, billing and workflow status for all licenses.
     * Updates based on expiration date:
     * - Within 2 months of expiry: renewal = open, billing = pending, workflow = payment_pending
     * - Expired: renewal = expired, billing = pending, workflow = expired
     * - More than 2 months until expiry: renewal = closed, billing = closed, workflow = active (if approved)
     */
    public function bulkRefreshStatus()
    {
        $user = Auth::user();
        
        // Only users with edit permission can bulk refresh
        if (!$user->hasPermission('edit')) {
            abort(403, 'Unauthorized action.');
        }

        $licenses = License::whereNotNull('expiration_date')->get();
        
        $updated = 0;
        $openedCount = 0;
        $closedCount = 0;
        $expiredCount = 0;

        foreach ($licenses as $license) {
            $oldRenewalStatus = $license->renewal_status;
            $oldBillingStatus = $license->billing_status;
            $oldWorkflowStatus = $license->workflow_status;
            
            // Update renewal and billing status based on expiration date
            $license->updateRenewalBillingStatus();
            
            // Also update workflow status based on expiration date for approved/active licenses
            if (in_array($oldWorkflowStatus, [
                License::WORKFLOW_APPROVED,
                License::WORKFLOW_ACTIVE,
                License::WORKFLOW_PAYMENT_PENDING,
                License::WORKFLOW_PAYMENT_COMPLETED,
                License::WORKFLOW_COMPLETED,
            ])) {
                if ($license->hasExpired()) {
                    $license->update(['workflow_status' => License::WORKFLOW_EXPIRED]);
                } elseif ($license->isWithinRenewalWindow()) {
                    // If within renewal window and billing is not yet paid, set to payment pending
                    if (!in_array($license->billing_status, [License::BILLING_PAID, License::BILLING_OVERRIDDEN])) {
                        $license->update(['workflow_status' => License::WORKFLOW_PAYMENT_PENDING]);
                    }
                } elseif ($oldWorkflowStatus === License::WORKFLOW_EXPIRED && !$license->hasExpired()) {
                    // Was expired but now has a future date (extended), set back to active
                    $license->update(['workflow_status' => License::WORKFLOW_ACTIVE]);
                }
            }
            
            $license->refresh();
            
            // Count changes
            if ($oldRenewalStatus !== $license->renewal_status || 
                $oldBillingStatus !== $license->billing_status ||
                $oldWorkflowStatus !== $license->workflow_status) {
                $updated++;
            }
            
            // Count by new status
            if ($license->renewal_status === License::RENEWAL_OPEN) {
                $openedCount++;
            } elseif ($license->renewal_status === License::RENEWAL_EXPIRED) {
                $expiredCount++;
            } else {
                $closedCount++;
            }
        }

        $message = "Bulk refresh completed! {$updated} license(s) updated. ";
        $message .= "Status summary: {$openedCount} open (within 2 months), {$expiredCount} expired, {$closedCount} closed.";

        return redirect()
            ->route('admin.licenses.index')
            ->with('success', $message);
    }

    /**
     * Extend/renew the license expiration date (Agent/Admin only)
     * Available after payment is completed
     */
    public function extendExpiration(Request $request, License $license)
    {
        $role = Auth::user()->Role->name;
        
        if (!in_array($role, ['Admin', 'Agent'])) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow extension if billing is paid or overridden
        if (!in_array($license->billing_status, [License::BILLING_PAID, License::BILLING_OVERRIDDEN])) {
            return redirect()
                ->route('admin.licenses.show', $license)
                ->with('error', 'Cannot extend license. Payment must be completed first.');
        }

        $validated = $request->validate([
            'new_expiration_date' => 'required|date|after:today',
            'renewal_evidence_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $oldExpiration = $license->expiration_date ? $license->expiration_date->format('M d, Y') : 'N/A';
        
        // Handle file upload
        $renewalEvidencePath = $license->renewal_evidence_file;
        if ($request->hasFile('renewal_evidence_file')) {
            // Delete old file if exists
            if ($license->renewal_evidence_file && Storage::disk('public')->exists($license->renewal_evidence_file)) {
                Storage::disk('public')->delete($license->renewal_evidence_file);
            }
            
            $file = $request->file('renewal_evidence_file');
            $filename = 'license_' . $license->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $renewalEvidencePath = $file->storeAs('renewal_evidence', $filename, 'public');
        }

        // Update the expiration date
        $license->update([
            'expiration_date' => $validated['new_expiration_date'],
            'renewal_evidence_file' => $renewalEvidencePath,
            'workflow_status' => License::WORKFLOW_ACTIVE,
            'renewal_status' => License::RENEWAL_CLOSED,
            'billing_status' => License::BILLING_CLOSED,
        ]);

        // Refresh status based on new date (in case it's within 2 months)
        $license->updateRenewalBillingStatus();

        $newExpiration = $license->expiration_date->format('M d, Y');

        // Send notification to client user (bell notification + email)
        if ($license->client) {
            $license->client->notify(new LicenseRenewedNotification(
                $license,
                $newExpiration,
                $renewalEvidencePath
            ));
        }

        // Also send email to the license's billing email(s) if different from client email
        if ($license->email) {
            // Handle multiple emails (comma-separated)
            $billingEmails = array_map('trim', explode(',', $license->email));
            foreach ($billingEmails as $billingEmail) {
                if (filter_var($billingEmail, FILTER_VALIDATE_EMAIL)) {
                    // Skip if it's the same as client email to avoid duplicate
                    if ($license->client && $license->client->email === $billingEmail) {
                        continue;
                    }
                    Mail::to($billingEmail)->send(new LicenseRenewedMail(
                        $license,
                        $newExpiration,
                        $renewalEvidencePath
                    ));
                }
            }
        }

        return redirect()
            ->route('admin.licenses.show', $license)
            ->with('success', "License extended! Previous expiration: {$oldExpiration} → New expiration: {$newExpiration}. Email notification sent to client.");
    }

    /**
     * Update license workflow status (Admin/Agent only)
     */
    public function updateStatus(Request $request, License $license)
    {
        $role = Auth::user()->Role->name;
        
        if (!in_array($role, ['Admin', 'Agent'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'workflow_status' => 'required|string|in:' . implode(',', [
                License::STATUS_PENDING,
                License::STATUS_SUBMITTED,
                License::STATUS_UNDER_REVIEW,
                License::STATUS_APPROVED,
                License::STATUS_REJECTED,
                License::STATUS_ON_HOLD,
                License::STATUS_CANCELLED,
            ]),
        ]);

        $oldStatus = $license->workflow_status;
        $newStatus = $validated['workflow_status'];

        if ($oldStatus !== $newStatus) {
            $license->update(['workflow_status' => $newStatus]);

            // Notify client about status change
            $client = $license->client;
            if ($client) {
                $client->notify(new LicenseStatusNotification($license, $oldStatus, $newStatus, Auth::user()->name));
            }
        }

        return redirect()
            ->route('admin.licenses.show', $license)
            ->with('success', 'License status updated to ' . ucfirst(str_replace('_', ' ', $newStatus)));
    }

    /**
     * Send notification when a new license is created
     */
    protected function notifyLicenseCreated(License $license): void
    {
        // Get all admins and agents to notify
        $staffUsers = User::whereHas('Role', function($query) {
            $query->whereIn('name', ['Admin', 'Agent']);
        })->where('id', '!=', Auth::id())->get();

        // Notify admins and agents (except the creator)
        foreach ($staffUsers as $user) {
            $user->notify(new LicenseCreatedNotification($license, Auth::user()));
        }

        // Notify client if created by admin/agent on their behalf
        if ($license->client_id && $license->client_id != Auth::id()) {
            $client = $license->client;
            if ($client) {
                $client->notify(new LicenseCreatedNotification($license, Auth::user()));
            }
        }
    }

    /**
     * Send notification when license status changes
     */
    protected function notifyStatusChange(License $license, string $oldStatus, string $newStatus): void
    {
        // Notify client
        $client = $license->client;
        if ($client) {
            $client->notify(new LicenseStatusNotification($license, $oldStatus, $newStatus, Auth::user()->name));
        }

        // Notify assigned agent if different from current user (from latest payment)
        $agent = $license->assignedAgent;
        if ($agent && $agent->id != Auth::id()) {
            $agent->notify(new LicenseStatusNotification($license, $oldStatus, $newStatus, Auth::user()->name));
        }
    }

    /**
     * Send renewal status notification
     */
    protected function notifyRenewalStatus(License $license): void
    {
        // Notify client
        $client = $license->client;
        if ($client) {
            $client->notify(new RenewalStatusNotification($license, $license->renewal_status));
        }

        // Notify assigned agent (from latest payment)
        $agent = $license->assignedAgent;
        if ($agent) {
            $agent->notify(new RenewalStatusNotification($license, $license->renewal_status));
        }
    }

    /**
     * Send billing status notification
     */
    protected function notifyBillingStatus(License $license, ?float $amount = null): void
    {
        // Notify client
        $client = $license->client;
        if ($client) {
            $client->notify(new BillingStatusNotification($license, $license->billing_status, $amount));
        }

        // Notify assigned agent (from latest payment)
        $agent = $license->assignedAgent;
        if ($agent) {
            $agent->notify(new BillingStatusNotification($license, $license->billing_status, $amount));
        }
    }
}
