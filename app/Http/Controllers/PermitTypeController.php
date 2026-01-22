<?php

namespace App\Http\Controllers;

use App\Models\PermitType;
use App\Models\PermitSubType;
use Illuminate\Http\Request;

class PermitTypeController extends Controller
{
    /**
     * Display a listing of the permit types.
     */
    public function index()
    {
        $permitTypes = PermitType::with('subPermits')->latest()->get();
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
            'is_active' => 'boolean',
            'sub_permits' => 'nullable|array',
            'sub_permits.*.name' => 'required|string|max:255',
            'sub_permits.*.is_active' => 'boolean',
        ]);

        $permitType = PermitType::create([
            'permit_type' => $validated['permit_type'],
            'is_active' => $request->has('is_active'),
        ]);

        // Create sub-permits if provided
        if ($request->has('sub_permits')) {
            foreach ($request->sub_permits as $subPermit) {
                if (!empty($subPermit['name'])) {
                    $permitType->subPermits()->create([
                        'name' => $subPermit['name'],
                        'is_active' => isset($subPermit['is_active']),
                    ]);
                }
            }
        }

        return redirect()->route('admin.permit-types.index')
            ->with('success', 'Permit Type created successfully.');
    }

    /**
     * Display the specified permit type.
     */
    public function show(PermitType $permitType)
    {
        $permitType->load('subPermits');
        return view('files.permit-types.show', compact('permitType'));
    }

    /**
     * Show the form for editing the specified permit type.
     */
    public function edit(PermitType $permitType)
    {
        $permitType->load('subPermits');
        return view('files.permit-types.edit', compact('permitType'));
    }

    /**
     * Update the specified permit type in storage.
     */
    public function update(Request $request, PermitType $permitType)
    {
        $validated = $request->validate([
            'permit_type' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sub_permits' => 'nullable|array',
            'sub_permits.*.id' => 'nullable|integer',
            'sub_permits.*.name' => 'required|string|max:255',
            'sub_permits.*.is_active' => 'boolean',
        ]);

        $permitType->update([
            'permit_type' => $validated['permit_type'],
            'is_active' => $request->has('is_active'),
        ]);

        // Handle sub-permits
        $existingIds = [];
        if ($request->has('sub_permits')) {
            foreach ($request->sub_permits as $subPermit) {
                if (!empty($subPermit['name'])) {
                    if (!empty($subPermit['id'])) {
                        // Update existing sub-permit
                        $existingSubPermit = PermitSubType::find($subPermit['id']);
                        if ($existingSubPermit && $existingSubPermit->permit_type_id == $permitType->id) {
                            $existingSubPermit->update([
                                'name' => $subPermit['name'],
                                'is_active' => isset($subPermit['is_active']),
                            ]);
                            $existingIds[] = $existingSubPermit->id;
                        }
                    } else {
                        // Create new sub-permit
                        $newSubPermit = $permitType->subPermits()->create([
                            'name' => $subPermit['name'],
                            'is_active' => isset($subPermit['is_active']),
                        ]);
                        $existingIds[] = $newSubPermit->id;
                    }
                }
            }
        }

        // Delete sub-permits that were removed
        $permitType->subPermits()->whereNotIn('id', $existingIds)->delete();

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
}
