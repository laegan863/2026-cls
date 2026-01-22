@extends('layouts.index')

@section('title', $title)

@section('content')
    <x-page-header :title="$title" :subtitle="$subtitle">
        <x-button href="{{ route('admin.dashboard') }}" variant="outline" icon="bi bi-arrow-left">Back to Dashboard</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-12">
            <x-card :title="$title . ' (' . $licenses->total() . ' total)'" icon="bi bi-list-ul" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>#</th>
                            <th>Store Name</th>
                            @if(!($isClient ?? false))
                            <th>Client</th>
                            @endif
                            <th>Permit Type</th>
                            <th>Jurisdiction</th>
                            <th>Expiration Date</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </x-slot:head>
                    
                    @forelse($licenses as $index => $license)
                        <tr>
                            <td>{{ $licenses->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <x-avatar :name="$license->store_name ?? 'N/A'" size="sm" />
                                    <div>
                                        <div class="fw-medium">{{ $license->store_name ?? 'N/A' }}</div>
                                        @if($license->store_address)
                                            <small class="text-muted">{{ Str::limit($license->store_address, 30) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            @if(!($isClient ?? false))
                            <td>
                                @if($license->client)
                                    <div class="fw-medium">{{ $license->client->name }}</div>
                                    <small class="text-muted">{{ $license->client->email }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            @endif
                            <td>
                                <div>{{ $license->permit_type }}</div>
                                @if($license->permit_subtype)
                                    <small class="text-muted">{{ $license->permit_subtype }}</small>
                                @endif
                            </td>
                            <td>
                                @if($license->jurisdiction_city || $license->jurisdiction_state)
                                    <div>{{ $license->jurisdiction_city }}</div>
                                    <small class="text-muted">{{ $license->jurisdiction_state }}, {{ $license->jurisdiction_country }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $isOverdue = \Carbon\Carbon::parse($license->expiration_date)->isPast();
                                    $isDueSoon = \Carbon\Carbon::parse($license->expiration_date)->diffInDays(now()) <= 30;
                                @endphp
                                <div class="fw-medium {{ $isOverdue ? 'text-danger' : ($isDueSoon ? 'text-warning' : '') }}">
                                    {{ \Carbon\Carbon::parse($license->expiration_date)->format('M d, Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($license->expiration_date)->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                @switch($license->workflow_status)
                                    @case('active')
                                        <x-badge variant="success">Active</x-badge>
                                        @break
                                    @case('pending_validation')
                                        <x-badge variant="warning">Pending Validation</x-badge>
                                        @break
                                    @case('requirements_pending')
                                        <x-badge variant="info">Requirements Pending</x-badge>
                                        @break
                                    @case('approved')
                                        <x-badge variant="primary">Approved</x-badge>
                                        @break
                                    @case('expired')
                                        <x-badge variant="danger">Expired</x-badge>
                                        @break
                                    @default
                                        <x-badge variant="secondary">{{ ucfirst(str_replace('_', ' ', $license->workflow_status)) }}</x-badge>
                                @endswitch

                                @if($license->renewal_status)
                                    <br>
                                    <small class="text-muted mt-1">Renewal: {{ ucfirst($license->renewal_status) }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <x-button href="{{ route('admin.licenses.show', $license) }}" variant="outline-primary" size="sm" icon="bi bi-eye" title="View License"></x-button>
                                    @if(Auth::user()->Role->name != 'Client')
                                    <x-button href="{{ route('admin.licenses.edit', $license) }}" variant="outline-warning" size="sm" icon="bi bi-pencil" title="Edit"></x-button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ ($isClient ?? false) ? 7 : 8 }}" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0">No licenses found for this filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </x-table>

                @if($licenses->hasPages())
                    <div class="p-3 border-top">
                        {{ $licenses->withQueryString()->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
@endsection
