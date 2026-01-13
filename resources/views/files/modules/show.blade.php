@extends('layouts.index')

@section('title', 'View Module')

@section('content')
    <x-page-header title="View Module" subtitle="Module details and role access.">
        <div class="d-flex gap-2">
            <x-button href="{{ route('admin.modules.edit', $module) }}" variant="warning" icon="bi bi-pencil">Edit</x-button>
            <x-button href="{{ route('admin.modules.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Modules</x-button>
        </div>
    </x-page-header>

    <div class="row">
        <div class="col-lg-6">
            <x-card title="Module Information" icon="bi bi-grid-3x3-gap">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Name:</th>
                        <td>
                            @if($module->icon)
                                <i class="{{ $module->icon }} me-2"></i>
                            @endif
                            {{ $module->name }}
                            @if($module->is_coming_soon)
                                <span class="text-danger small">(Coming Soon)</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td><code>{{ $module->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Route:</th>
                        <td>
                            @if($module->route)
                                <code>{{ $module->route }}</code>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Parent:</th>
                        <td>
                            @if($module->parent)
                                <x-badge variant="info">{{ $module->parent->name }}</x-badge>
                            @else
                                <span class="text-muted">Root Module</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Order:</th>
                        <td>{{ $module->order }}</td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td>{{ $module->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($module->is_active)
                                <x-badge variant="success">Active</x-badge>
                            @else
                                <x-badge variant="danger">Inactive</x-badge>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $module->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                </table>
            </x-card>

            @if($module->children->count() > 0)
                <x-card title="Child Modules ({{ $module->children->count() }})" icon="bi bi-diagram-3" class="mt-4">
                    <ul class="list-group list-group-flush">
                        @foreach($module->children as $child)
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <div>
                                    @if($child->icon)
                                        <i class="{{ $child->icon }} me-2"></i>
                                    @endif
                                    {{ $child->name }}
                                    @if($child->is_coming_soon)
                                        <span class="text-danger small">(Coming Soon)</span>
                                    @endif
                                </div>
                                <x-button href="{{ route('admin.modules.show', $child) }}" variant="outline-info" size="sm" icon="bi bi-eye"></x-button>
                            </li>
                        @endforeach
                    </ul>
                </x-card>
            @endif
        </div>

        <div class="col-lg-6">
            <x-card title="Roles with Access ({{ $module->roles->count() }})" icon="bi bi-shield-lock">
                @if($module->roles->count() > 0)
                    <x-table>
                        <x-slot:head>
                            <tr>
                                <th>Role</th>
                                <th class="text-center">View</th>
                                <th class="text-center">Create</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                            </tr>
                        </x-slot:head>
                        @foreach($module->roles as $role)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.roles.show', $role) }}">{{ $role->name }}</a>
                                </td>
                                <td class="text-center">
                                    @if($role->pivot->can_view)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($role->pivot->can_create)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($role->pivot->can_edit)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($role->pivot->can_delete)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </x-table>
                @else
                    <p class="text-muted mb-0">No roles have access to this module.</p>
                @endif
            </x-card>
        </div>
    </div>
@endsection
