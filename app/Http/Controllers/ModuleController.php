<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    /**
     * Display a listing of the modules.
     */
    public function index()
    {
        $modules = Module::with('parent')
            ->withCount('roles')
            ->orderBy('order')
            ->get();
        
        return view('files.modules.index', compact('modules'));
    }

    /**
     * Show the form for creating a new module.
     */
    public function create()
    {
        $parentModules = Module::whereNull('parent_id')->orderBy('order')->get();
        return view('files.modules.create', compact('parentModules'));
    }

    /**
     * Store a newly created module in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:modules,slug',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:modules,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_coming_soon' => 'boolean',
        ]);

        Module::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? null,
            'route' => $validated['route'] ?? null,
            'description' => $validated['description'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
            'is_coming_soon' => $request->has('is_coming_soon'),
        ]);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module created successfully.');
    }

    /**
     * Display the specified module.
     */
    public function show(Module $module)
    {
        $module->load(['parent', 'children', 'roles']);
        return view('files.modules.show', compact('module'));
    }

    /**
     * Show the form for editing the specified module.
     */
    public function edit(Module $module)
    {
        $parentModules = Module::whereNull('parent_id')
            ->where('id', '!=', $module->id)
            ->orderBy('order')
            ->get();
        
        return view('files.modules.edit', compact('module', 'parentModules'));
    }

    /**
     * Update the specified module in storage.
     */
    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:modules,slug,' . $module->id,
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:modules,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_coming_soon' => 'boolean',
        ]);

        $module->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? null,
            'route' => $validated['route'] ?? null,
            'description' => $validated['description'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
            'is_coming_soon' => $request->has('is_coming_soon'),
        ]);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module updated successfully.');
    }

    /**
     * Remove the specified module from storage.
     */
    public function destroy(Module $module)
    {
        // Check if module has children
        if ($module->children()->count() > 0) {
            return redirect()->route('admin.modules.index')
                ->with('error', 'Cannot delete module with child modules.');
        }

        $module->roles()->detach();
        $module->delete();

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module deleted successfully.');
    }

    /**
     * Reorder modules via AJAX.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'modules' => 'required|array',
            'modules.*.id' => 'required|exists:modules,id',
            'modules.*.order' => 'required|integer',
        ]);

        foreach ($validated['modules'] as $moduleData) {
            Module::where('id', $moduleData['id'])->update(['order' => $moduleData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Modules reordered successfully.',
        ]);
    }
}
