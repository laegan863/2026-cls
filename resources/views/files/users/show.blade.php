@extends('layouts.index')

@section('title', 'View User')

@section('content')
    <x-page-header title="View User" subtitle="User details and role information.">
        <div class="d-flex gap-2">
            <x-button href="{{ route('admin.users.edit', $user) }}" variant="warning" icon="bi bi-pencil">Edit</x-button>
            <x-button href="{{ route('admin.users.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Users</x-button>
        </div>
    </x-page-header>

    <div class="row">
        <div class="col-lg-6">
            <x-card title="User Information" icon="bi bi-person">
                <div class="text-center mb-4">
                    <x-avatar name="{{ $user->name }}" size="lg" />
                    <h4 class="mt-3 mb-1">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    @if($user->is_active)
                        <x-badge variant="success">Active</x-badge>
                    @else
                        <x-badge variant="danger">Inactive</x-badge>
                    @endif
                </div>
                
                <hr>
                
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Contact No:</th>
                        <td>{{ $user->contact_no ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Role:</th>
                        <td>
                            @if($user->role)
                                <x-badge variant="primary">{{ $user->role->name }}</x-badge>
                            @else
                                <span class="text-muted">No role assigned</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Email Verified:</th>
                        <td>
                            @if($user->email_verified_at)
                                <x-badge variant="success">Verified</x-badge>
                                <small class="text-muted d-block">{{ $user->email_verified_at->format('M d, Y h:i A') }}</small>
                            @else
                                <x-badge variant="warning">Not Verified</x-badge>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $user->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td>{{ $user->updated_at->format('M d, Y h:i A') }}</td>
                    </tr>
                </table>
            </x-card>
        </div>

        <div class="col-lg-6">
            @if($user->role)
                <x-card title="Role Permissions" icon="bi bi-shield-lock">
                    <p class="mb-2">
                        <strong>Role:</strong> {{ $user->role->name }}
                    </p>
                    @if($user->role->description)
                        <p class="text-muted small mb-3">{{ $user->role->description }}</p>
                    @endif
                    
                    @if($user->role->permissions->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($user->role->permissions as $permission)
                                <x-badge variant="secondary">{{ $permission->name }}</x-badge>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No permissions assigned to this role.</p>
                    @endif
                </x-card>
            @else
                <x-card title="Role Information" icon="bi bi-shield-lock">
                    <x-empty-state 
                        icon="bi bi-shield-exclamation" 
                        title="No Role Assigned" 
                        description="This user does not have a role assigned."
                    />
                </x-card>
            @endif

            <x-card title="Quick Actions" icon="bi bi-lightning" class="mt-4">
                <div class="d-grid gap-2">
                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} w-100">
                            <i class="bi bi-{{ $user->is_active ? 'x-circle' : 'check-circle' }} me-1"></i>
                            {{ $user->is_active ? 'Deactivate User' : 'Activate User' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> Edit User
                    </a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-1"></i> Delete User
                        </button>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
@endsection
