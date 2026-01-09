<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions', 'modules'])->latest()->get();
        return view('files.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::where('is_active', true)->get();
        $modules = Module::whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->where('is_active', true)->orderBy('order');
            }])
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('files.roles.create', compact('permissions', 'modules'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'modules' => 'array',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        // Sync module permissions
        if (!empty($validated['modules'])) {
            $role->syncModulesWithPermissions($validated['modules']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users', 'modules']);
        return view('files.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::where('is_active', true)->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        $modules = Module::whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->where('is_active', true)->orderBy('order');
            }])
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        // Get role's module permissions
        $roleModules = $role->modules->mapWithKeys(function ($module) {
            return [$module->id => [
                'can_view' => $module->pivot->can_view,
                'can_create' => $module->pivot->can_create,
                'can_edit' => $module->pivot->can_edit,
                'can_delete' => $module->pivot->can_delete,
            ]];
        })->toArray();
        
        return view('files.roles.edit', compact('role', 'permissions', 'rolePermissions', 'modules', 'roleModules'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'modules' => 'array',
        ]);

        $role->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);
        
        // Sync module permissions
        $role->syncModulesWithPermissions($validated['modules'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role with assigned users.');
        }

        $role->permissions()->detach();
        $role->modules()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
