@extends('layouts.index')

@section('title', 'Permissions Management')

@section('content')
    <x-page-header title="Permissions Management" subtitle="Manage system permissions.">
        <x-button href="{{ route('admin.permissions.create') }}" variant="gold" icon="bi bi-plus-lg">Add New Permission</x-button>
    </x-page-header>

    @if(session('success'))
        <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="danger" dismissible>{{ session('error') }}</x-alert>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <x-card title="All Permissions" icon="bi bi-key" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th>Roles</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </x-slot:head>

                    @forelse ($permissions as $index => $permission)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $permission->name }}</strong>
                            </td>
                            <td><code>{{ $permission->slug }}</code></td>
                            <td>{{ $permission->module ?? '-' }}</td>
                            <td>{{ Str::limit($permission->description, 40) ?? '-' }}</td>
                            <td>
                                <x-badge variant="secondary">{{ $permission->roles_count }} roles</x-badge>
                            </td>
                            <td>
                                @if($permission->is_active)
                                    <x-badge variant="success">Active</x-badge>
                                @else
                                    <x-badge variant="danger">Inactive</x-badge>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <x-button href="{{ route('admin.permissions.show', $permission) }}" variant="outline-info" size="sm" icon="bi bi-eye" title="View"></x-button>
                                    <x-button href="{{ route('admin.permissions.edit', $permission) }}" variant="outline-warning" size="sm" icon="bi bi-pencil" title="Edit"></x-button>
                                    <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this permission?')">
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
                                    icon="bi bi-key" 
                                    title="No permissions found" 
                                    description="Get started by creating a new permission."
                                />
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>
        </div>
    </div>
@endsection
