<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyController extends Controller
{
    /**
     * Display a listing of the agencies.
     */
    public function index()
    {
        $agencies = Agency::latest()->get();
        return view('files.agencies.index', compact('agencies'));
    }

    /**
     * Show the form for creating a new agency.
     */
    public function create()
    {
        return view('files.agencies.create');
    }

    /**
     * Store a newly created agency in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:agencies,name',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Agency::create($validated);

        return redirect()->route('admin.agency.index')
            ->with('success', 'Agency created successfully.');
    }

    /**
     * Display the specified agency.
     */
    public function show(Agency $agency)
    {
        return view('files.agencies.show', compact('agency'));
    }

    /**
     * Show the form for editing the specified agency.
     */
    public function edit(Agency $agency)
    {
        return view('files.agencies.edit', compact('agency'));
    }

    /**
     * Update the specified agency in storage.
     */
    public function update(Request $request, Agency $agency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:agencies,name,' . $agency->id,
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $agency->update($validated);

        return redirect()->route('admin.agency.index')
            ->with('success', 'Agency updated successfully.');
    }

    /**
     * Remove the specified agency from storage.
     */
    public function destroy(Agency $agency)
    {
        $agency->delete();

        return redirect()->route('admin.agency.index')
            ->with('success', 'Agency deleted successfully.');
    }
}
