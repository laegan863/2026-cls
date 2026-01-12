<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LicenseController extends Controller
{
    /**
     * Display a listing of the licenses.
     */
    public function index()
    {
        $role = Auth::user()->Role->name;
        
        // Admin and Agent can see all licenses, Client can only see their own
        if ($role === 'Admin' || $role === 'Agent') {
            $licenses = License::with(['client', 'assignedAgent'])->latest()->get();
        } else {
            // Client - only show their own licenses
            $licenses = License::with(['client', 'assignedAgent'])
                ->where('client_id', Auth::id())
                ->latest()
                ->get();
        }
        
        return view('files.licensing', compact('licenses'));
    }

    /**
     * Show the form for creating a new license.
     */
    public function create()
    {
        return view('files.add-new-license-user');
    }

    /**
     * Store a newly created license in storage.
     */
    public function store(Request $request)
    {
        $role = Auth::user()->Role->name;
        
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
        ]);

        if ($role === 'Admin') {
            $validated['client_id'] = $request->input('client_id');
        } else {
            $validated['client_id'] = Auth::id();
        }

        if ($request->filled('assigned_agent')) {
            $agent = User::whereHas('Role', function ($query) {
                $query->where('name', 'Agent');
            })->where('id', $request->assigned_agent)->first();
            
            $validated['assigned_agent_id'] = $agent ? $agent->id : null;
        }
        
        unset($validated['assigned_agent']);
        $validated['transaction_id'] = Str::random(12);
        License::create($validated);

        return redirect()->route('admin.licenses.index')->with('success', 'License created successfully!');
    }

    public function show(License $license)
    {
        $role = Auth::user()->Role->name;
        
        if ($role === 'Client' && $license->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('files.license-show', compact('license'));
    }

    public function edit(License $license)
    {
        $role = Auth::user()->Role->name;
        
        if ($role === 'Client' && $license->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('files.license-edit', compact('license'));
    }

    public function update(Request $request, License $license)
    {
        $role = Auth::user()->Role->name;
        
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
            'assigned_agent' => 'nullable|string',
            'renewal_status' => 'nullable|string',
            'billing_status' => 'nullable|string',
            'submission_confirmation_number' => 'nullable|string',
        ]);

        $license->update($validated);
        return redirect()->route('admin.licenses.index')->with('success', 'License updated successfully!');
    }

    public function destroy(License $license)
    {
        $role = Auth::user()->Role->name;
        
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
        $role = Auth::user()->Role->name;
        
        if (!in_array($role, ['Admin', 'Agent'])) {
            abort(403, 'Unauthorized action.');
        }

        $license->updateRenewalBillingStatus();
        
        return redirect()
            ->route('admin.licenses.show', $license)
            ->with('success', 'Renewal and billing status updated. Renewal: ' . $license->renewal_status_label . ', Billing: ' . $license->billing_status_label);
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
}
