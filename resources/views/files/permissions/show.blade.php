@extends('layouts.index')

@section('title', 'View Permission')

@section('content')
    <x-page-header title="View Permission" subtitle="Permission details and assigned roles.">
        <div class="d-flex gap-2">
            <x-button href="{{ route('admin.permissions.edit', $permission) }}" variant="warning" icon="bi bi-pencil">Edit</x-button>
            <x-button href="{{ route('admin.permissions.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Permissions</x-button>
        </div>
    </x-page-header>

    <div class="row">
        <div class="col-lg-6">
            <x-card title="Permission Information" icon="bi bi-key">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Name:</th>
                        <td>{{ $permission->name }}</td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td><code>{{ $permission->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Module:</th>
                        <td>{{ $permission->module ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td>{{ $permission->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($permission->is_active)
                                <x-badge variant="success">Active</x-badge>
                            @else
                                <x-badge variant="danger">Inactive</x-badge>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $permission->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated:</th>
                        <td>{{ $permission->updated_at->format('M d, Y h:i A') }}</td>
                    </tr>
                </table>
            </x-card>
        </div>

        <div class="col-lg-6">
            <x-card title="Roles with this Permission ({{ $permission->roles->count() }})" icon="bi bi-shield">
                @if($permission->roles->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($permission->roles as $role)
                            <a href="{{ route('admin.roles.show', $role) }}">
                                <x-badge variant="primary">{{ $role->name }}</x-badge>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">This permission is not assigned to any role.</p>
                @endif
            </x-card>
        </div>
    </div>
@endsection
