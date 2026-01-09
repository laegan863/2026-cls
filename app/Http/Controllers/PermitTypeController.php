<?php

namespace App\Http\Controllers;

use App\Models\PermitType;
use Illuminate\Http\Request;

class PermitTypeController extends Controller
{
    /**
     * Display a listing of the permit types.
     */
    public function index()
    {
        $permitTypes = PermitType::latest()->get();
        return view('files.permit-types.index', compact('permitTypes'));
    }

    /**
     * Show the form for creating a new permit type.
     */
    public function create()
    {
        return view('files.permit-types.create');
    }

    /**
     * Store a newly created permit type in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'permit_type' => 'required|string|max:255',
            'sub_type' => 'nullable|array',
            'sub_type.*' => 'string|max:255',
            'is_active' => 'boolean',
        ]);

        // Filter out empty sub_type values
        $subTypes = array_filter($validated['sub_type'] ?? [], fn($value) => !empty(trim($value)));

        PermitType::create([
            'permit_type' => $validated['permit_type'],
            'sub_type' => !empty($subTypes) ? array_values($subTypes) : null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.permit-types.index')
            ->with('success', 'Permit Type created successfully.');
    }

    /**
     * Display the specified permit type.
     */
    public function show(PermitType $permitType)
    {
        return view('files.permit-types.show', compact('permitType'));
    }

    /**
     * Show the form for editing the specified permit type.
     */
    public function edit(PermitType $permitType)
    {
        return view('files.permit-types.edit', compact('permitType'));
    }

    /**
     * Update the specified permit type in storage.
     */
    public function update(Request $request, PermitType $permitType)
    {
        $validated = $request->validate([
            'permit_type' => 'required|string|max:255',
            'sub_type' => 'nullable|array',
            'sub_type.*' => 'string|max:255',
            'is_active' => 'boolean',
        ]);

        // Filter out empty sub_type values
        $subTypes = array_filter($validated['sub_type'] ?? [], fn($value) => !empty(trim($value)));

        $permitType->update([
            'permit_type' => $validated['permit_type'],
            'sub_type' => !empty($subTypes) ? array_values($subTypes) : null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.permit-types.index')
            ->with('success', 'Permit Type updated successfully.');
    }

    /**
     * Remove the specified permit type from storage.
     */
    public function destroy(PermitType $permitType)
    {
        $permitType->delete();

        return redirect()->route('admin.permit-types.index')
            ->with('success', 'Permit Type deleted successfully.');
    }

    /**
     * Add a sub type to an existing permit type via AJAX.
     */
    public function addSubType(Request $request, PermitType $permitType)
    {
        $validated = $request->validate([
            'sub_type' => 'required|string|max:255',
        ]);

        $permitType->addSubType($validated['sub_type']);

        return response()->json([
            'success' => true,
            'message' => 'Sub type added successfully.',
            'sub_types' => $permitType->sub_type,
        ]);
    }

    /**
     * Remove a sub type from an existing permit type via AJAX.
     */
    public function removeSubType(Request $request, PermitType $permitType)
    {
        $validated = $request->validate([
            'sub_type' => 'required|string|max:255',
        ]);

        $permitType->removeSubType($validated['sub_type']);

        return response()->json([
            'success' => true,
            'message' => 'Sub type removed successfully.',
            'sub_types' => $permitType->sub_type,
        ]);
    }
}
