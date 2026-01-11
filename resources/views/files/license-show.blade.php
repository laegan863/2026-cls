@extends('layouts.index')

@section('title', 'View License')

@section('content')
    <x-page-header title="View License" subtitle="License details for {{ $license->legal_name ?? 'N/A' }}">
        <x-button href="{{ route('licenses.index') }}" variant="secondary" icon="bi bi-arrow-left">Back</x-button>
        <x-button href="{{ route('licenses.edit', $license) }}" variant="gold" icon="bi bi-pencil">Edit</x-button>
    </x-page-header>

    <div class="my-2">
        <x-card title="Client Information" icon="bi bi-person-fill">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Client Name</label>
                    <p class="form-control-plaintext">{{ $license->client->name ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Billing Email(s)</label>
                    <p class="form-control-plaintext">{{ $license->email ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Primary Contact Info</label>
                    <p class="form-control-plaintext">{{ $license->primary_contact_info ?? 'N/A' }}</p>
                </div>
            </div>
        </x-card>
    </div>

    <div class="my-2">
        <x-card title="Business Entity" icon="bi bi-building">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Legal Name</label>
                    <p class="form-control-plaintext">{{ $license->legal_name ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">DBA</label>
                    <p class="form-control-plaintext">{{ $license->dba ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">FEIN</label>
                    <p class="form-control-plaintext">{{ $license->fein ?? 'N/A' }}</p>
                </div>
            </div>
        </x-card>
    </div>

    <div class="my-2">
        <x-card title="Store / Location (Primary Operating Unit)" icon="bi bi-geo-alt-fill">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Country</label>
                    <p class="form-control-plaintext">{{ $license->country ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">State</label>
                    <p class="form-control-plaintext">{{ $license->state ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">City</label>
                    <p class="form-control-plaintext">{{ $license->city ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Zip Code</label>
                    <p class="form-control-plaintext">{{ $license->zip_code ?? 'N/A' }}</p>
                </div>
            </div>
        </x-card>
    </div>

    <div class="my-2">
        <x-card title="Permit / License Details" icon="bi bi-file-earmark-text-fill">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Permit Type</label>
                    <p class="form-control-plaintext">
                        <x-badge type="primary">{{ ucfirst(str_replace('_', ' ', $license->permit_type ?? 'N/A')) }}</x-badge>
                    </p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Permit Subtype</label>
                    <p class="form-control-plaintext">{{ ucfirst(str_replace('_', ' ', $license->permit_subtype ?? 'N/A')) }}</p>
                </div>
            </div>
        </x-card>
    </div>

    <div class="my-2">
        <x-card title="Jurisdiction" icon="bi bi-globe">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Jurisdiction Country</label>
                    <p class="form-control-plaintext">{{ $license->jurisdiction_country ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Jurisdiction State</label>
                    <p class="form-control-plaintext">{{ $license->jurisdiction_state ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Jurisdiction City</label>
                    <p class="form-control-plaintext">{{ $license->jurisdiction_city ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Federal</label>
                    <p class="form-control-plaintext">{{ $license->jurisdiction_federal ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Agency Name</label>
                    <p class="form-control-plaintext">{{ $license->agency_name ?? 'N/A' }}</p>
                </div>
            </div>
        </x-card>
    </div>

    <div class="my-2">
        <x-card title="Dates & Status" icon="bi bi-calendar-event">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Expiration Date</label>
                    <p class="form-control-plaintext">{{ $license->expiration_date ? $license->expiration_date->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Renewal Window Open Date</label>
                    <p class="form-control-plaintext">{{ $license->renewal_window_open_date ? $license->renewal_window_open_date->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Assigned Agent</label>
                    <p class="form-control-plaintext">{{ $license->assignedAgent->name ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Renewal Status</label>
                    <p class="form-control-plaintext">
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
                    </p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Billing Status</label>
                    <p class="form-control-plaintext">
                        @php
                            $billingType = match($license->billing_status) {
                                'paid_online', 'paid_offline' => 'success',
                                'invoiced', 'payment_pending' => 'warning',
                                'not_invoiced' => 'secondary',
                                'voided' => 'danger',
                                'override_approved' => 'info',
                                default => 'secondary'
                            };
                        @endphp
                        <x-badge type="{{ $billingType }}">{{ ucfirst(str_replace('_', ' ', $license->billing_status ?? 'N/A')) }}</x-badge>
                    </p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Submission Confirmation Number</label>
                    <p class="form-control-plaintext">{{ $license->submission_confirmation_number ?? 'N/A' }}</p>
                </div>
            </div>
        </x-card>
    </div>

    <div class="my-3 d-flex justify-content-end gap-2">
        <x-button href="{{ route('licenses.index') }}" variant="secondary">Back to List</x-button>
        <x-button href="{{ route('licenses.edit', $license) }}" variant="gold" icon="bi bi-pencil">Edit License</x-button>
    </div>
@endsection
