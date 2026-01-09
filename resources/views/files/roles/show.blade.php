@extends('layouts.index')

@section('title', 'View Role')

@section('content')
    <x-page-header title="View Role" subtitle="Role details and assigned permissions.">
        <div class="d-flex gap-2">
            <x-button href="{{ route('admin.roles.edit', $role) }}" variant="warning" icon="bi bi-pencil">Edit</x-button>
            <x-button href="{{ route('admin.roles.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Roles</x-button>
        </div>
    </x-page-header>

    <div class="row">
        <div class="col-lg-6">
            <x-card title="Role Information" icon="bi bi-shield-lock">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Name:</th>
                        <td>{{ $role->name }}</td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td><code>{{ $role->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td>{{ $role->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($role->is_active)
                                <x-badge variant="success">Active</x-badge>
                            @else
                                <x-badge variant="danger">Inactive</x-badge>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $role->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated:</th>
                        <td>{{ $role->updated_at->format('M d, Y h:i A') }}</td>
                    </tr>
                </table>
            </x-card>

            <x-card title="Assigned Permissions ({{ $role->permissions->count() }})" icon="bi bi-key" class="mt-4">
                @if($role->permissions->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($role->permissions as $permission)
                            <x-badge variant="primary">{{ $permission->name }}</x-badge>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No permissions assigned to this role.</p>
                @endif
            </x-card>
        </div>

        <div class="col-lg-6">
            <x-card title="Module Access ({{ $role->modules->count() }})" icon="bi bi-grid-3x3-gap">
                @if($role->modules->count() > 0)
                    <x-table>
                        <x-slot:head>
                            <tr>
                                <th>Module</th>
                                <th class="text-center">View</th>
                                <th class="text-center">Create</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                            </tr>
                        </x-slot:head>
                        @foreach($role->modules as $module)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($module->icon)
                                            <i class="{{ $module->icon }}"></i>
                                        @endif
                                        {{ $module->name }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($module->pivot->can_view)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($module->pivot->can_create)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($module->pivot->can_edit)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($module->pivot->can_delete)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </x-table>
                @else
                    <p class="text-muted mb-0">No module access configured for this role.</p>
                @endif
            </x-card>

            <x-card title="Users with this Role ({{ $role->users->count() }})" icon="bi bi-people" class="mt-4">
                @if($role->users->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($role->users->take(10) as $user)
                            <li class="list-group-item d-flex align-items-center">
                                <x-avatar name="{{ $user->name }}" size="sm" class="me-2" />
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    <br><small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @if($role->users->count() > 10)
                        <p class="text-muted mt-2 mb-0">And {{ $role->users->count() - 10 }} more users...</p>
                    @endif
                @else
                    <p class="text-muted mb-0">No users assigned to this role.</p>
                @endif
            </x-card>
        </div>
    </div>
@endsection
