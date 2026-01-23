@extends('layouts.index')

@section('title', 'Upcoming Renewals')

@section('content')
    @php
        $role = Auth::user()->Role->name;
    @endphp

    <x-page-header title="Upcoming Renewals" subtitle="Licenses expiring within the next 2 months">
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
                <form action="{{ route('admin.upcoming-renewals.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Store Name</label>
                            <x-input type="text" name="store_name" placeholder="Search by Store Name" :value="request('store_name')" />
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
                                <x-button href="{{ route('admin.upcoming-renewals.index') }}" variant="outline">Clear</x-button>
                            </div>
                        </div>
                    </div>
                </form>
            </x-card>

            <!-- Upcoming Renewals Table -->
            <x-card title="Upcoming Renewals {{ $role === 'Client' ? '(My Renewals)' : '(All Licenses)' }}" icon="fas fa-calendar-alt" class="mb-4" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>Store Name</th>
                            @if(Auth::user()->Role->name !== 'Client')
                                <th>Client Name</th>
                            @endif
                            <th>Permit Type</th>
                            <th>Renewal Expiration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </x-slot:head>

                    @forelse($upcomingRenewals as $renewal)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <x-avatar name="{{ $renewal->store_name ?? 'N/A' }}" size="sm" />
                                    <a href="{{ route('admin.licenses.show', $renewal) }}">
                                        <span class="text-dark">{{ $renewal->store_name ?? 'N/A' }}</span>
                                    </a>
                                </div>
                            </td>
                            @if(Auth::user()->Role->name !== 'Client')
                                <td>{{ $renewal->client->name ?? 'N/A' }}</td>
                            @endif
                            <td>{{ ucfirst(str_replace('_', ' ', $renewal->permit_type ?? 'N/A')) }}</td>
                            <td>
                                @if($renewal->expiration_date)
                                    {{ $renewal->expiration_date->format('M d, Y') }}
                                    @php $daysUntil = $renewal->days_until_expiration; @endphp
                                    @if($daysUntil < 0)
                                        <br><small class="text-danger fw-bold">Expired {{ abs($daysUntil) }} days ago</small>
                                    @elseif($daysUntil <= 30)
                                        <br><small class="text-danger fw-bold">{{ $daysUntil }} days left</small>
                                    @elseif($daysUntil <= 60)
                                        <br><small class="text-warning">{{ $daysUntil }} days left</small>
                                    @else
                                        <br><small class="text-muted">{{ $daysUntil }} days left</small>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <x-badge type="{{ $renewal->renewal_status_badge ?? 'secondary' }}">{{ $renewal->renewal_status_label ?? 'Closed' }}</x-badge>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if(Auth::user()->hasPermission('view'))
                                    <x-icon-button href="{{ route('admin.licenses.show', $renewal) }}" icon="fas fa-eye" variant="primary" size="sm" title="View Details" />
                                    @endif

                                    @if($renewal->renewal_status === 'open' || $renewal->renewal_status === 'expired')
                                        @if(!$renewal->activePayment)
                                            <form action="{{ route('admin.licenses.initiate-renewal', $renewal) }}" method="POST" class="d-inline">
                                                @csrf
                                                <x-icon-button type="submit" icon="fas fa-sync-alt" variant="success" size="sm" title="Renew License" />
                                            </form>
                                        @else
                                            <x-icon-button href="{{ route('admin.licenses.payments.show', $renewal) }}" icon="fas fa-credit-card" variant="warning" size="sm" title="View Payment" />
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->Role->name !== 'Client' ? '6' : '5' }}" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-calendar-check fs-1 d-block mb-2"></i>
                                    No upcoming renewals found.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>
        </div>
    </div>
@endsection
