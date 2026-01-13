@extends('layouts.index')

@section('title', 'Create Role')

@section('content')
    <x-page-header title="Create Role" subtitle="Add a new role to the system.">
        <x-button href="{{ route('admin.roles.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Roles</x-button>
    </x-page-header>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-lg-6">
                <x-card title="Role Information" icon="bi bi-shield-plus">
                    <div class="mb-3">
                        <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                        <x-input name="name" placeholder="Enter role name" value="{{ old('name') }}" required />
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <x-textarea name="description" placeholder="Enter role description" rows="3">{{ old('description') }}</x-textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <x-checkbox name="is_active" value="1" :checked="old('is_active', true)" label="Active" />
                    </div>

                    @if($permissions->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            @php
                                $groupedPermissions = $permissions->groupBy('module');
                            @endphp
                            @foreach($groupedPermissions as $moduleName => $modulePermissions)
                                <div class="mb-3">
                                    <div class="fw-semibold text-muted small text-uppercase mb-2">{{ $moduleName ?: 'General' }}</div>
                                    <div class="row">
                                        @foreach($modulePermissions as $permission)
                                            <div class="col-md-6 mb-2">
                                                <x-checkbox 
                                                    name="permissions[]" 
                                                    value="{{ $permission->id }}" 
                                                    :checked="in_array($permission->id, old('permissions', []))" 
                                                    label="{{ $permission->name }}" 
                                                    id="permission_{{ $permission->id }}"
                                                />
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-card>
            </div>

            <div class="col-lg-6">
                <x-card title="Module Access Permissions" icon="bi bi-grid-3x3-gap">
                    <p class="text-muted small mb-3">Select which modules this role can access.</p>
                    
                    @if($modules->count() > 0)
                        <div class="module-access-list">
                            @foreach($modules as $module)
                                <div class="module-item d-flex align-items-center justify-content-between py-2 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($module->icon)
                                            <i class="{{ $module->icon }} text-primary"></i>
                                        @endif
                                        <span class="fw-medium">{{ $module->name }}</span>
                                        @if($module->is_coming_soon)
                                            <span class="badge bg-warning text-dark" style="font-size: 0.6rem">Soon</span>
                                        @endif
                                    </div>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" role="switch"
                                            name="modules[{{ $module->id }}][has_access]" value="1"
                                            id="module_{{ $module->id }}"
                                            {{ old("modules.{$module->id}.has_access") ? 'checked' : '' }}>
                                    </div>
                                </div>
                                @if($module->children && $module->children->count() > 0)
                                    @foreach($module->children as $child)
                                        <div class="module-item d-flex align-items-center justify-content-between py-2 border-bottom ps-4 bg-light">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-arrow-return-right text-muted"></i>
                                                @if($child->icon)
                                                    <i class="{{ $child->icon }} text-secondary"></i>
                                                @endif
                                                <span>{{ $child->name }}</span>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" role="switch"
                                                    name="modules[{{ $child->id }}][has_access]" value="1"
                                                    id="module_{{ $child->id }}"
                                                    {{ old("modules.{$child->id}.has_access") ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No modules available. <a href="{{ route('admin.modules.create') }}">Create one</a>.</p>
                    @endif
                </x-card>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <x-button type="submit" variant="gold" icon="bi bi-check-lg">Create Role</x-button>
            <x-button href="{{ route('admin.roles.index') }}" variant="outline">Cancel</x-button>
        </div>
    </form>
@endsection
