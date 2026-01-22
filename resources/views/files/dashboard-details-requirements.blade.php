@extends('layouts.index')

@section('title', $title)

@section('content')
    <x-page-header :title="$title" :subtitle="$subtitle">
        <x-button href="{{ route('admin.dashboard') }}" variant="outline" icon="bi bi-arrow-left">Back to Dashboard</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-12">
            <x-card :title="$title . ' (' . $requirements->total() . ' total)'" icon="bi bi-bell-fill" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>#</th>
                            <th>Store</th>
                            <th>License / Permit</th>
                            <th>Requirement</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </x-slot:head>
                    
                    @forelse($requirements as $index => $requirement)
                        @php
                            $license = $requirement->license;
                        @endphp
                        <tr>
                            <td>{{ $requirements->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <x-avatar :name="$license->store_name ?? 'N/A'" size="sm" />
                                    <div>
                                        <div class="fw-medium">{{ $license->store_name ?? 'N/A' }}</div>
                                        @if($license->store_address)
                                            <small class="text-muted">{{ Str::limit($license->store_address, 25) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $license->permit_type }}</div>
                                @if($license->permit_subtype)
                                    <small class="text-muted">{{ $license->permit_subtype }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium">{{ $requirement->label }}</div>
                                @if($requirement->description)
                                    <small class="text-muted">{{ Str::limit($requirement->description, 40) }}</small>
                                @endif
                            </td>
                            <td>
                                @switch($requirement->status)
                                    @case('pending')
                                        <x-badge variant="warning">
                                            <i class="bi bi-clock me-1"></i> Pending
                                        </x-badge>
                                        @break
                                    @case('rejected')
                                        <x-badge variant="danger">
                                            <i class="bi bi-x-circle me-1"></i> Rejected
                                        </x-badge>
                                        @if($requirement->rejection_reason)
                                            <div class="mt-1">
                                                <small class="text-danger" title="{{ $requirement->rejection_reason }}">
                                                    <i class="bi bi-info-circle"></i> {{ Str::limit($requirement->rejection_reason, 30) }}
                                                </small>
                                            </div>
                                        @endif
                                        @break
                                    @case('submitted')
                                        <x-badge variant="info">
                                            <i class="bi bi-send me-1"></i> Submitted
                                        </x-badge>
                                        @break
                                    @case('approved')
                                        <x-badge variant="success">
                                            <i class="bi bi-check-circle me-1"></i> Approved
                                        </x-badge>
                                        @break
                                    @default
                                        <x-badge variant="secondary">{{ ucfirst($requirement->status) }}</x-badge>
                                @endswitch
                            </td>
                            <td>
                                @if($requirement->creator)
                                    <div class="d-flex align-items-center gap-2">
                                        <x-avatar :name="$requirement->creator->name" size="sm" variant="gold" />
                                        <span>{{ $requirement->creator->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium">{{ $requirement->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $requirement->created_at->diffForHumans() }}</small>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <x-button href="{{ route('admin.licenses.requirements', $license) }}" variant="outline-primary" size="sm" icon="bi bi-file-text" title="View Requirements"></x-button>
                                    <x-button href="{{ route('admin.licenses.show', $license) }}" variant="outline-info" size="sm" icon="bi bi-eye" title="View License"></x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-check-circle fs-1 text-success d-block mb-2"></i>
                                <p class="text-muted mb-0">Great! No items need your attention right now.</p>
                            </td>
                        </tr>
                    @endforelse
                </x-table>

                @if($requirements->hasPages())
                    <div class="p-3 border-top">
                        {{ $requirements->withQueryString()->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    @if(($isClient ?? false) && $requirements->count() > 0)
    <div class="row mt-4">
        <div class="col-lg-12">
            <x-alert type="info" icon="bi bi-info-circle">
                <strong>Action Required:</strong> Please review and respond to the requirements listed above. 
                Click on "View Requirements" to upload documents or provide the requested information.
            </x-alert>
        </div>
    </div>
    @endif
@endsection
