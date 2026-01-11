<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Handle client_id based on role
        if ($role === 'Admin') {
            $validated['client_id'] = $request->input('client_id');
        } else {
            // Client creates their own license
            $validated['client_id'] = Auth::id();
        }

        // Handle assigned agent - find user by value or set null
        if ($request->filled('assigned_agent')) {
            $agent = User::whereHas('Role', function ($query) {
                $query->where('name', 'Agent');
            })->where('id', $request->assigned_agent)->first();
            
            $validated['assigned_agent_id'] = $agent ? $agent->id : null;
        }
        
        unset($validated['assigned_agent']);

        License::create($validated);

        return redirect()->route('licenses.index')->with('success', 'License created successfully!');
    }

    /**
     * Display the specified license.
     */
    public function show(License $license)
    {
        $role = Auth::user()->Role->name;
        
        // Client can only view their own licenses
        if ($role === 'Client' && $license->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('files.license-show', compact('license'));
    }

    /**
     * Show the form for editing the specified license.
     */
    public function edit(License $license)
    {
        $role = Auth::user()->Role->name;
        
        // Client can only edit their own licenses
        if ($role === 'Client' && $license->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('files.license-edit', compact('license'));
    }

    /**
     * Update the specified license in storage.
     */
    public function update(Request $request, License $license)
    {
        $role = Auth::user()->Role->name;
        
        // Client can only update their own licenses
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

        return redirect()->route('licenses.index')->with('success', 'License updated successfully!');
    }

    /**
     * Remove the specified license from storage.
     */
    public function destroy(License $license)
    {
        $role = Auth::user()->Role->name;
        
        // Client can only delete their own licenses
        if ($role === 'Client' && $license->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        $license->delete();

        return redirect()->route('licenses.index')->with('success', 'License deleted successfully!');
    }
}
