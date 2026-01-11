<?php

namespace App\Http\Controllers;

use App\Models\PermitSubType;
use Illuminate\Http\Request;

class PermitSubTypeController extends Controller
{
    public function index()
    {
        $subTypes = PermitSubType::latest()->get();
        return view('files.permit-sub-types.index', compact('subTypes'));
    }

    public function create()
    {
        return view('files.permit-sub-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        PermitSubType::create([
            'name' => $validated['name'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.permit-sub-types.index')->with('success', 'Permit sub type created.');
    }

    public function show(PermitSubType $permitSubType)
    {
        return view('files.permit-sub-types.show', ['subType' => $permitSubType]);
    }

    public function edit(PermitSubType $permitSubType)
    {
        return view('files.permit-sub-types.edit', ['subType' => $permitSubType]);
    }

    public function update(Request $request, PermitSubType $permitSubType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $permitSubType->update([
            'name' => $validated['name'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.permit-sub-types.index')->with('success', 'Permit sub type updated.');
    }

    public function destroy(PermitSubType $permitSubType)
    {
        $permitSubType->delete();
        return redirect()->route('admin.permit-sub-types.index')->with('success', 'Permit sub type deleted.');
    }
}
