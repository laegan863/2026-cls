@extends('layouts.index')

@section('title', 'Roles Management')

@section('content')
    <x-page-header title="Roles Management" subtitle="Manage user roles and their permissions.">
        <x-button href="{{ route('admin.roles.create') }}" variant="gold" icon="bi bi-plus-lg">Add New Role</x-button>
    </x-page-header>

    @if(session('success'))
        <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="danger" dismissible>{{ session('error') }}</x-alert>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <x-card title="All Roles" icon="bi bi-shield-lock" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Users</th>
                            <th>Permissions</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </x-slot:head>

                    @forelse ($roles as $index => $role)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $role->name }}</strong>
                            </td>
                            <td><code>{{ $role->slug }}</code></td>
                            <td>{{ Str::limit($role->description, 50) ?? '-' }}</td>
                            <td>
                                <x-badge variant="info">{{ $role->users_count }} users</x-badge>
                            </td>
                            <td>
                                <x-badge variant="secondary">{{ $role->permissions_count }} permissions</x-badge>
                            </td>
                            <td>
                                @if($role->is_active)
                                    <x-badge variant="success">Active</x-badge>
                                @else
                                    <x-badge variant="danger">Inactive</x-badge>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <x-button href="{{ route('admin.roles.show', $role) }}" variant="outline-info" size="sm" icon="bi bi-eye" title="View"></x-button>
                                    <x-button href="{{ route('admin.roles.edit', $role) }}" variant="outline-warning" size="sm" icon="bi bi-pencil" title="Edit"></x-button>
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="outline-danger" size="sm" icon="bi bi-trash" title="Delete"></x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <x-empty-state 
                                    icon="bi bi-shield-lock" 
                                    title="No roles found" 
                                    description="Get started by creating a new role."
                                />
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>
        </div>
    </div>
@endsection
