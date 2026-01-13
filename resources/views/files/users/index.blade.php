@extends('layouts.index')

@section('title', 'User Management')

@section('content')
    <x-page-header title="User Management" subtitle="Manage system users and their roles.">
        <x-button href="{{ route('admin.users.create') }}" variant="gold" icon="bi bi-plus-lg">Add New User</x-button>
    </x-page-header>

    @if(session('success'))
        <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="danger" dismissible>{{ session('error') }}</x-alert>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <!-- Filters -->
            <x-card title="Filters" icon="bi bi-funnel" class="mb-4">
                <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <x-input name="search" placeholder="Name, email or contact..." value="{{ request('search') }}" />
                    </div>
                    <div class="col-md-3">
                        <label for="role" class="form-label">Filter by Role</label>
                        <x-select name="role" placeholder="All Roles">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <x-select name="status" placeholder="All Status">
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <x-button type="submit" variant="primary" icon="bi bi-search">Filter</x-button>
                        <x-button href="{{ route('admin.users.index') }}" variant="outline">Clear</x-button>
                    </div>
                </form>
            </x-card>

            <x-card title="All Users" icon="bi bi-people" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Contact No</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </x-slot:head>

                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <x-avatar name="{{ $user->name }}" size="sm" />
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        <br><small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->contact_no ?? '-' }}</td>
                            <td>
                                @if($user->role)
                                    <x-badge variant="primary">{{ $user->role->name }}</x-badge>
                                @else
                                    <span class="text-muted">No role</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <x-badge variant="success">Active</x-badge>
                                @else
                                    <x-badge variant="danger">Inactive</x-badge>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <x-button href="{{ route('admin.users.show', $user) }}" variant="outline-info" size="sm" icon="bi bi-eye" title="View"></x-button>
                                    <x-button href="{{ route('admin.users.edit', $user) }}" variant="outline-warning" size="sm" icon="bi bi-pencil" title="Edit"></x-button>
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($user->is_active)
                                            <x-button type="submit" variant="outline-secondary" size="sm" icon="bi bi-x-circle" title="Deactivate"></x-button>
                                        @else
                                            <x-button type="submit" variant="outline-success" size="sm" icon="bi bi-check-circle" title="Activate"></x-button>
                                        @endif
                                    </form>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="outline-danger" size="sm" icon="bi bi-trash" title="Delete"></x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <x-empty-state 
                                    icon="bi bi-people" 
                                    title="No users found" 
                                    description="Get started by creating a new user or adjust your filters."
                                />
                            </td>
                        </tr>
                    @endforelse
                </x-table>
                
                @if($users->hasPages())
                    <div class="p-3">
                        {{ $users->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
@endsection
