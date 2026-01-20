@extends('layouts.index')

@section('title', 'Licensing & Permitting')

@section('content')
    @php
        $role = Auth::user()->Role->name;
    @endphp

    <x-page-header title="Licensing & Permitting" subtitle="Welcome back, {{ Auth::user()->name }}! Here's what's happening today.">
        @if($role === 'Admin' || $role === 'Agent')
            <form action="{{ route('admin.licenses.bulk-refresh') }}" method="POST" class="d-inline me-2">
                @csrf
                <x-button type="submit" variant="primary" icon="bi bi-arrow-clockwise">Bulk Refresh Status</x-button>
            </form>
        @endif
        <x-button href="{{ route('admin.licenses.create') }}" variant="gold" icon="bi bi-plus-lg">Add New</x-button>
    </x-page-header>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="row">
        <div class="col-lg-12">
            <!-- Filter Section -->
            <x-card class="mb-3">
                <form action="{{ route('admin.licenses.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Transaction ID</label>
                            <x-input type="text" name="transaction_id" placeholder="Search by Transaction ID" :value="request('transaction_id')" />
                        </div>
                        @if($role === 'Admin' || $role === 'Agent')
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Client Name</label>
                            <x-select name="client_id">
                                <option value="">All Clients</option>
                                @php
                                    $clients = \App\Models\User::whereHas('role', function($q) {
                                        $q->where('slug', 'client');
                                    })->orderBy('name')->get();
                                @endphp
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
                        @endif
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Renewal Status</label>
                            <x-select name="renewal_status">
                                <option value="">All Status</option>
                                <option value="open" {{ request('renewal_status') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ request('renewal_status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="expired" {{ request('renewal_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </x-select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <x-button type="submit" variant="primary" icon="bi bi-search">Filter</x-button>
                                <x-button href="{{ route('admin.licenses.index') }}" variant="outline">Clear</x-button>
                            </div>
                        </div>
                    </div>
                </form>
            </x-card>

            <x-card title="Licenses List {{ $role === 'Client' ? '(My Licenses)' : '(All Licenses)' }}" icon="fas fa-table" class="mb-4" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>Store Name</th>
                            {{-- <th>Transaction ID</th> --}}
                            @if(Auth::user()->Role->name !== 'Client')
                                <th>Permit Type</th>
                                <th>Sub Permit Type</th>
                                <th>Location</th>
                                <th>Renewal</th>
                                <th>Expiration</th>
                            @endif
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </x-slot:head>

                    @forelse($licenses as $license)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <x-avatar name="{{ $license->store_name ?? 'N/A' }}" size="sm" />
                                    <span>{{ $license->store_name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            {{-- <td>
                                {{ $license->transaction_id ?? '' }}
                            </td> --}}
                            @if(Auth::user()->Role->name !== 'Client')
                                <td>
                                    {{ ucfirst(str_replace('_', ' ', $license->permit_type ?? 'N/A')) }}
                                </td>
                                <td>
                                    {{ $license->sub_permit_type ?? 'N/A' }}
                                </td>
                                <td>{{ $license->city ?? '' }}{{ $license->city && $license->state ? ', ' : '' }}{{ $license->state ?? '' }}</td>
                                <td>
                                    <x-badge type="{{ $license->renewal_status_badge ?? 'secondary' }}">{{ $license->renewal_status_label ?? 'Closed' }}</x-badge>
                                </td>
                                <td>
                                    @if($license->expiration_date)
                                        {{ $license->expiration_date->format('M d, Y') }}
                                        @php $daysUntil = $license->days_until_expiration; @endphp
                                        @if($daysUntil < 0)
                                            <br><small class="text-danger">Expired</small>
                                        @elseif($daysUntil <= 60)
                                            <br><small class="text-warning">{{ $daysUntil }} days</small>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                            @endif
                            <td>
                                <x-badge type="warning">{{ ucfirst(str_replace('_', ' ', $license->workflow_status_label ?? 'N/A')) }}</x-badge>
                            </td>
                            <td>
                                <x-dropdown align="end">
                                    <x-slot:trigger>
                                        <x-icon-button icon="fas fa-ellipsis-v" variant="light" size="sm" />
                                    </x-slot:trigger>
                                    @if(Auth::user()->hasPermission('view'))
                                    <x-dropdown-item href="{{ route('admin.licenses.show', $license) }}" icon="fas fa-eye">View</x-dropdown-item>
                                    @endif

                                    @if(Auth::user()->hasPermission('edit'))
                                    <x-dropdown-item href="{{ route('admin.licenses.edit', $license) }}" icon="fas fa-edit">Edit</x-dropdown-item>
                                    @endif

                                    @if(Auth::user()->hasPermission('delete'))
                                    <x-dropdown-divider />
                                    <form action="{{ route('admin.licenses.destroy', $license) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this license?')">Delete</button>
                                    </form>
                                    @endif
                                </x-dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No licenses found. <a href="{{ route('admin.licenses.create') }}">Add your first license</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>
        </div>
    </div>
@endsection
