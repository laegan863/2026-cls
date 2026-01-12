<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\LicenseRequirement;
use App\Notifications\RequirementAddedNotification;
use App\Notifications\RequirementStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LicenseRequirementController extends Controller
{
    /**
     * Display requirements for a license
     */
    public function index(License $license)
    {
        $this->authorizeAccess($license);
        
        $requirements = $license->requirements()->with(['creator', 'reviewer'])->get();
        
        return view('files.license-requirements', compact('license', 'requirements'));
    }

    /**
     * Store new requirements (Agent/Admin only)
     */
    public function store(Request $request, License $license)
    {
        $this->authorizeAdminAgent();

        $validated = $request->validate([
            'requirements' => 'required|array|min:1',
            'requirements.*.label' => 'required|string|max:255',
            'requirements.*.description' => 'nullable|string',
        ]);

        foreach ($validated['requirements'] as $req) {
            $license->requirements()->create([
                'label' => $req['label'],
                'description' => $req['description'] ?? null,
                'created_by' => Auth::id(),
                'status' => LicenseRequirement::STATUS_PENDING,
            ]);
        }

        // Update license workflow status
        $license->markAsRequirementsPending();

        // Notify client
        $license->client->notify(new RequirementAddedNotification($license));

        return redirect()
            ->route('admin.licenses.requirements.index', $license)
            ->with('success', 'Requirements added successfully. Client has been notified.');
    }

    /**
     * Submit requirement value (Client)
     */
    public function submit(Request $request, License $license, LicenseRequirement $requirement)
    {
        // Client can only submit their own license requirements
        if (Auth::user()->Role->name === 'Client' && $license->client_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'value' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('requirements/' . $license->id, 'public');
        }

        $requirement->markAsSubmitted($validated['value'] ?? null, $filePath);

        // Check if all requirements are submitted
        $allSubmitted = $license->requirements()
            ->whereIn('status', [LicenseRequirement::STATUS_PENDING, LicenseRequirement::STATUS_REJECTED])
            ->count() === 0;

        if ($allSubmitted) {
            $license->markAsRequirementsSubmitted();
        }

        return redirect()
            ->route('admin.licenses.requirements.index', $license)
            ->with('success', 'Requirement submitted successfully.');
    }

    /**
     * Approve a requirement (Agent/Admin)
     */
    public function approve(License $license, LicenseRequirement $requirement)
    {
        $this->authorizeAdminAgent();

        $requirement->approve(Auth::id());

        // Check if all requirements are approved
        if ($license->allRequirementsApproved()) {
            // Apply appropriate status based on expiration date
            $license->applyPostApprovalStatus(Auth::id());

            // Notify client of approval
            $license->client->notify(new RequirementStatusNotification($license, 'approved'));
        }

        return redirect()
            ->route('admin.licenses.requirements.index', $license)
            ->with('success', 'Requirement approved.');
    }

    /**
     * Reject a requirement (Agent/Admin)
     */
    public function reject(Request $request, License $license, LicenseRequirement $requirement)
    {
        $this->authorizeAdminAgent();

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $requirement->reject(Auth::id(), $validated['rejection_reason']);
        
        // Update license status back to requirements pending
        $license->markAsRequirementsPending();

        // Notify client
        $license->client->notify(new RequirementStatusNotification($license, 'rejected', $validated['rejection_reason']));

        return redirect()
            ->route('admin.licenses.requirements.index', $license)
            ->with('success', 'Requirement rejected. Client has been notified.');
    }

    /**
     * Approve license directly without requirements (Agent/Admin)
     */
    public function approveLicense(License $license)
    {
        $this->authorizeAdminAgent();

        if (!$license->allRequirementsApproved()) {
            return redirect()
                ->route('admin.licenses.requirements.index', $license)
                ->with('error', 'Cannot approve license. Some requirements are not approved.');
        }

        // Apply appropriate status based on expiration date
        $license->applyPostApprovalStatus(Auth::id());

        // Notify client
        $license->client->notify(new RequirementStatusNotification($license, 'approved'));

        $statusMessage = match($license->workflow_status) {
            License::WORKFLOW_ACTIVE => 'License approved and is now active.',
            License::WORKFLOW_PAYMENT_PENDING => 'License approved. Payment is required (within 2 months of expiration).',
            License::WORKFLOW_EXPIRED => 'License approved but has already expired.',
            default => 'License approved successfully.',
        };

        return redirect()
            ->route('admin.licenses.show', $license)
            ->with('success', $statusMessage);
    }

    /**
     * Reject license (Agent/Admin)
     */
    public function rejectLicense(Request $request, License $license)
    {
        $this->authorizeAdminAgent();

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $license->reject(Auth::id(), $validated['rejection_reason']);

        // Notify client
        $license->client->notify(new RequirementStatusNotification($license, 'rejected', $validated['rejection_reason']));

        return redirect()
            ->route('admin.licenses.show', $license)
            ->with('success', 'License rejected.');
    }

    /**
     * Delete a requirement (Agent/Admin)
     */
    public function destroy(License $license, LicenseRequirement $requirement)
    {
        $this->authorizeAdminAgent();

        // Delete file if exists
        if ($requirement->file_path) {
            Storage::disk('public')->delete($requirement->file_path);
        }

        $requirement->delete();

        // If no more requirements, check workflow status
        if ($license->requirements()->count() === 0) {
            $license->update(['workflow_status' => License::WORKFLOW_PENDING_VALIDATION]);
        }

        return redirect()
            ->route('admin.licenses.requirements.index', $license)
            ->with('success', 'Requirement deleted.');
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
}
