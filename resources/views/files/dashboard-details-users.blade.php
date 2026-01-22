@extends('layouts.index')

@section('title', $title)

@section('content')
    <x-page-header :title="$title" :subtitle="$subtitle">
        <x-button href="{{ route('admin.dashboard') }}" variant="outline" icon="bi bi-arrow-left">Back to Dashboard</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-12">
            <x-card :title="$title . ' (' . $users->total() . ' total)'" icon="bi bi-people" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </x-slot:head>
                    
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <x-avatar :name="$user->name" size="sm" />
                                    <div>
                                        <div class="fw-medium">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">{{ $user->email }}</a>
                            </td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                <x-badge variant="info">{{ $user->role->name ?? 'N/A' }}</x-badge>
                            </td>
                            <td>
                                @if($user->is_active)
                                    <x-badge variant="success">Active</x-badge>
                                @else
                                    <x-badge variant="danger">Inactive</x-badge>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium">{{ $user->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <x-button href="{{ route('admin.users.show', $user) }}" variant="outline-primary" size="sm" icon="bi bi-eye" title="View User"></x-button>
                                    <x-button href="{{ route('admin.users.edit', $user) }}" variant="outline-warning" size="sm" icon="bi bi-pencil" title="Edit"></x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0">No new users found this month.</p>
                            </td>
                        </tr>
                    @endforelse
                </x-table>

                @if($users->hasPages())
                    <div class="p-3 border-top">
                        {{ $users->withQueryString()->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
@endsection
