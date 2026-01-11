@extends('layouts.index')

@section('title', 'Licensing & Permitting')

@section('content')
    @php
        $role = Auth::user()->Role->name;
    @endphp

    <x-page-header title="Licensing & Permitting" subtitle="Welcome back, {{ Auth::user()->name }}! Here's what's happening today.">
        <x-button href="{{ route('licenses.create') }}" variant="gold" icon="bi bi-plus-lg">Add New</x-button>
    </x-page-header>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <x-card title="Licenses List {{ $role === 'Client' ? '(My Licenses)' : '(All Licenses)' }}" icon="fas fa-table" class="mb-4" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>Client Name</th>
                            <th>Legal Name</th>
                            <th>Permit Type</th>
                            <th>Location</th>
                            <th>Renewal Status</th>
                            <th>Expiration Date</th>
                            <th>Actions</th>
                        </tr>
                    </x-slot:head>

                    @forelse($licenses as $license)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <x-avatar name="{{ $license->client->name ?? 'N/A' }}" size="sm" />
                                    <span>{{ $license->client->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td>{{ $license->legal_name ?? 'N/A' }}</td>
                            <td>
                                <x-badge type="primary">{{ ucfirst(str_replace('_', ' ', $license->permit_type ?? 'N/A')) }}</x-badge>
                            </td>
                            <td>{{ $license->city ?? '' }}{{ $license->city && $license->state ? ', ' : '' }}{{ $license->state ?? '' }}</td>
                            <td>
                                @php
                                    $statusType = match($license->renewal_status) {
                                        'monitoring' => 'success',
                                        'approved_completed' => 'success',
                                        'submitted', 'resubmitted' => 'info',
                                        'pending_payment', 'pending_client_response' => 'warning',
                                        'failed_closed' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <x-badge type="{{ $statusType }}">{{ ucfirst(str_replace('_', ' ', $license->renewal_status ?? 'N/A')) }}</x-badge>
                            </td>
                            <td>{{ $license->expiration_date ? $license->expiration_date->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                <x-dropdown align="end">
                                    <x-slot:trigger>
                                        <x-icon-button icon="fas fa-ellipsis-v" variant="light" size="sm" />
                                    </x-slot:trigger>
                                    <x-dropdown-item href="{{ route('licenses.show', $license) }}" icon="fas fa-eye">View</x-dropdown-item>
                                    <x-dropdown-item href="{{ route('licenses.edit', $license) }}" icon="fas fa-edit">Edit</x-dropdown-item>
                                    <x-dropdown-divider />
                                    <form action="{{ route('licenses.destroy', $license) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <x-dropdown-item type="submit" icon="fas fa-trash" class="text-danger" onclick="return confirm('Are you sure you want to delete this license?')">Delete</x-dropdown-item>
                                    </form>
                                </x-dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No licenses found. <a href="{{ route('licenses.create') }}">Add your first license</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>
        </div>
    </div>
@endsection
