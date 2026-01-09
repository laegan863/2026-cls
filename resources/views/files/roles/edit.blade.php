@extends('layouts.index')

@section('title', 'Edit Role')

@section('content')
    <x-page-header title="Edit Role" subtitle="Update role information and permissions.">
        <x-button href="{{ route('admin.roles.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Roles</x-button>
    </x-page-header>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-6">
                <x-card title="Role Information" icon="bi bi-shield-check">
                    <div class="mb-3">
                        <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                        <x-input name="name" placeholder="Enter role name" value="{{ old('name', $role->name) }}" required />
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <x-textarea name="description" placeholder="Enter role description" rows="3">{{ old('description', $role->description) }}</x-textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <x-checkbox name="is_active" value="1" :checked="old('is_active', $role->is_active)" label="Active" />
                    </div>

                    @if($permissions->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <div class="row">
                                @foreach($permissions as $permission)
                                    <div class="col-md-6 mb-2">
                                        <x-checkbox 
                                            name="permissions[]" 
                                            value="{{ $permission->id }}" 
                                            :checked="in_array($permission->id, old('permissions', $rolePermissions))" 
                                            label="{{ $permission->name }}" 
                                        />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </x-card>
            </div>

            <div class="col-lg-6">
                <x-card title="Module Access Permissions" icon="bi bi-grid-3x3-gap">
                    <p class="text-muted small mb-3">Configure which modules this role can access and what actions are allowed.</p>
                    
                    @if($modules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Module</th>
                                        <th class="text-center" width="60">View</th>
                                        <th class="text-center" width="60">Create</th>
                                        <th class="text-center" width="60">Edit</th>
                                        <th class="text-center" width="60">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modules as $module)
                                        @php
                                            $modulePerms = $roleModules[$module->id] ?? null;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($module->icon)
                                                        <i class="{{ $module->icon }}"></i>
                                                    @endif
                                                    <span>{{ $module->name }}</span>
                                                    @if($module->is_coming_soon)
                                                        <span class="badge bg-warning text-dark" style="font-size: 0.6rem">Soon</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" class="form-check-input" 
                                                    name="modules[{{ $module->id }}][can_view]" value="1"
                                                    {{ old("modules.{$module->id}.can_view", $modulePerms['can_view'] ?? false) ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" class="form-check-input" 
                                                    name="modules[{{ $module->id }}][can_create]" value="1"
                                                    {{ old("modules.{$module->id}.can_create", $modulePerms['can_create'] ?? false) ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" class="form-check-input" 
                                                    name="modules[{{ $module->id }}][can_edit]" value="1"
                                                    {{ old("modules.{$module->id}.can_edit", $modulePerms['can_edit'] ?? false) ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" class="form-check-input" 
                                                    name="modules[{{ $module->id }}][can_delete]" value="1"
                                                    {{ old("modules.{$module->id}.can_delete", $modulePerms['can_delete'] ?? false) ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                        @if($module->children && $module->children->count() > 0)
                                            @foreach($module->children as $child)
                                                @php
                                                    $childPerms = $roleModules[$child->id] ?? null;
                                                @endphp
                                                <tr class="table-light">
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="bi bi-arrow-return-right text-muted"></i>
                                                            @if($child->icon)
                                                                <i class="{{ $child->icon }}"></i>
                                                            @endif
                                                            <span>{{ $child->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="form-check-input" 
                                                            name="modules[{{ $child->id }}][can_view]" value="1"
                                                            {{ old("modules.{$child->id}.can_view", $childPerms['can_view'] ?? false) ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="form-check-input" 
                                                            name="modules[{{ $child->id }}][can_create]" value="1"
                                                            {{ old("modules.{$child->id}.can_create", $childPerms['can_create'] ?? false) ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="form-check-input" 
                                                            name="modules[{{ $child->id }}][can_edit]" value="1"
                                                            {{ old("modules.{$child->id}.can_edit", $childPerms['can_edit'] ?? false) ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="form-check-input" 
                                                            name="modules[{{ $child->id }}][can_delete]" value="1"
                                                            {{ old("modules.{$child->id}.can_delete", $childPerms['can_delete'] ?? false) ? 'checked' : '' }}>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No modules available. <a href="{{ route('admin.modules.create') }}">Create one</a>.</p>
                    @endif
                </x-card>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <x-button type="submit" variant="gold" icon="bi bi-check-lg">Update Role</x-button>
            <x-button href="{{ route('admin.roles.index') }}" variant="outline">Cancel</x-button>
        </div>
    </form>
@endsection
