<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\User;
use App\Models\LicenseRequirement;
use App\Notifications\LicenseCreatedNotification;
use App\Notifications\LicenseStatusNotification;
use App\Notifications\RenewalStatusNotification;
use App\Notifications\BillingStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if ($request->filled('transaction_id')) {
            $query->where('transaction_id', 'like', '%' . $request->transaction_id . '%');
        }
        
        if ($request->filled('client_id') && ($role === 'Admin' || $role === 'Agent')) {
            $query->where('client_id', $request->client_id);
        }
        
        if ($request->filled('renewal_status')) {
            $query->where('renewal_status', $request->renewal_status);
        }
        
        $licenses = $query->latest()->get();
        
        return view('files.licensing', compact('licenses'));
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
            'email' => 'nullable|email',
            'primary_contact_info' => 'nullable|string',
            'legal_name' => 'nullable|string',
            'dba' => 'nullable|string',
            'fein' => 'nullable|string',
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
            'renewal_window_open_date' => 'nullable|date',
            'assigned_agent' => 'nullable|string',
            'renewal_status' => 'nullable|string',
            'billing_status' => 'nullable|string',
            'submission_confirmation_number' => 'nullable|string',
            // Requirements validation
            'requirements' => 'nullable|array',
            'requirements.*.label' => 'nullable|string|max:255',
            'requirements.*.description' => 'nullable|string',
            'requirements.*.value' => 'nullable|string',
            'requirement_files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240',
        ]);

        if ($role === 'Admin') {
            $validated['client_id'] = $request->input('client_id');
        } else {
            $validated['client_id'] = Auth::id();
        }

        unset($validated['assigned_agent']);
        // Remove requirements from validated data (they go to separate table)
        unset($validated['requirements']);
        unset($validated['requirement_files']);
        
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

        return redirect()->route('admin.licenses.index')->with('success', 'License created successfully!');
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
            'renewal_window_open_date' => 'nullable|date',
            'renewal_status' => 'nullable|string',
            'billing_status' => 'nullable|string',
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
        ]);

        $oldExpiration = $license->expiration_date ? $license->expiration_date->format('M d, Y') : 'N/A';
        
        // Update the expiration date
        $license->update([
            'expiration_date' => $validated['new_expiration_date'],
            'workflow_status' => License::WORKFLOW_ACTIVE,
            'renewal_status' => License::RENEWAL_CLOSED,
            'billing_status' => License::BILLING_CLOSED,
        ]);

        // Refresh status based on new date (in case it's within 2 months)
        $license->updateRenewalBillingStatus();

        $newExpiration = $license->expiration_date->format('M d, Y');

        return redirect()
            ->route('admin.licenses.show', $license)
            ->with('success', "License extended! Previous expiration: {$oldExpiration} → New expiration: {$newExpiration}");
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
