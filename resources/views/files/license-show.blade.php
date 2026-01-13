@extends('layouts.index')

@section('title', 'View License')

@section('content')
    @php
        $role = Auth::user()->Role->name;
        $isAdminAgent = in_array($role, ['Admin', 'Agent']);
    @endphp

    <x-page-header title="View License" subtitle="License details for {{ $license->legal_name ?? 'N/A' }}">
        <x-button href="{{ route('admin.licenses.index') }}" variant="primary" icon="bi bi-arrow-left">Back</x-button>
        <x-button href="{{ route('admin.licenses.edit', $license) }}" variant="gold" icon="bi bi-pencil">Edit</x-button>
    </x-page-header>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Workflow Status & Actions Card -->
    <div class="row mb-3">
        <div class="col-12">
            <x-card>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <h5 class="mb-1">Workflow Status</h5>
                        <x-badge type="{{ $license->workflow_status_badge ?? 'secondary' }}">{{ $license->workflow_status_label ?? 'Pending Validation' }}</x-badge>
                        
                        @if($license->expiration_date)
                            @php
                                $daysUntil = $license->days_until_expiration;
                            @endphp
                            @if($daysUntil !== null)
                                @if($daysUntil < 0)
                                    <x-badge type="danger">Expired {{ abs($daysUntil) }} days ago</x-badge>
                                @elseif($daysUntil <= 60)
                                    <x-badge type="warning">Expires in {{ $daysUntil }} days</x-badge>
                                @else
                                    <x-badge type="info">Expires in {{ $daysUntil }} days</x-badge>
                                @endif
                            @endif
                        @endif
                        
                        <!-- Renewal & Billing Status -->
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <small class="text-muted me-3">
                                <strong>Renewal:</strong>
                                <x-badge type="{{ $license->renewal_status_badge ?? 'secondary' }}">{{ $license->renewal_status_label ?? 'Closed' }}</x-badge>
                            </small>
                            <small class="text-muted">
                                <strong>Billing:</strong>
                                <x-badge type="{{ $license->billing_status_badge ?? 'secondary' }}">{{ $license->billing_status_label ?? 'Closed' }}</x-badge>
                            </small>
                            @if($isAdminAgent)
                                <form action="{{ route('admin.licenses.refresh-status', $license) }}" method="POST" class="d-inline ms-2">
                                    @csrf
                                    <x-button type="submit" variant="outline-secondary" size="sm" icon="bi bi-arrow-clockwise" title="Refresh Status"></x-button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <x-button href="{{ route('admin.licenses.requirements.index', $license) }}" variant="primary" icon="bi bi-list-check">
                            Requirements
                            @if($license->requirements()->count() > 0)
                                <span class="badge bg-light text-dark ms-1">{{ $license->requirements()->count() }}</span>
                            @endif
                        </x-button>
                        @if($isAdminAgent && $license->canCreatePayment())
                            <x-button href="{{ route('admin.licenses.payments.create', $license) }}" variant="gold" icon="bi bi-plus-circle">
                                Create Payment
                            </x-button>
                        @endif
                        <x-button href="{{ route('admin.licenses.payments.show', $license) }}" variant="primary" icon="bi bi-credit-card">
                            Payment
                            @if($license->activePayment)
                                <span class="badge bg-warning text-dark ms-1">Open</span>
                            @endif
                        </x-button>
                        @if($isAdminAgent && in_array($license->billing_status, ['paid', 'overridden']))
                            <x-button type="button" variant="warning" icon="bi bi-calendar-plus" data-bs-toggle="modal" data-bs-target="#extendExpirationModal">Extend License</x-button>
                        @endif
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <div class="my-2">
        <x-card class="mb-4" title="License Overview" icon="bi bi-info-circle-fill">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Transaction ID</label>
                    <p class="form-control-plaintext">{{ $license->transaction_id ?? 'N/A' }}</p>
                </div>
                {{-- <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <p class="form-control-plaintext">
                        <x-badge type="primary">{{ ucfirst(str_replace('_', ' ', $license->workflow_status_label ?? 'N/A')) }}</x-badge>
                    </p>
                </div> --}}
            </div>
        </x-card>
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
        <x-button href="{{ route('admin.licenses.index') }}" variant="primary" icon="bi bi-arrow-left">Back to List</x-button>
        <x-button href="{{ route('admin.licenses.edit', $license) }}" variant="gold" icon="bi bi-pencil">Edit License</x-button>
    </div>
@endsection

<script>
    // Dynamic Additional Fields
    let fieldCounter = 0;

    function addField() {
        fieldCounter++;
        const container = document.getElementById('dynamic-fields-container');
        
        const fieldRow = document.createElement('div');
        fieldRow.className = 'row mb-3 dynamic-field-row';
        fieldRow.id = `dynamic-field-${fieldCounter}`;
        
        fieldRow.innerHTML = `
            <div class="col-lg-4">
                <label class="form-label">Field Label</label>
                <x-input name="custom_fields[${fieldCounter}][label]" type="textarea" placeholder="Enter field label" />
            </div>
            <div class="col-lg-6">
                <label class="form-label">Field Value</label>
                <x-input name="custom_fields[${fieldCounter}][value]" type="textarea" placeholder="Enter field value" />
            </div>
            <div class="col-lg-2 d-flex align-items-end">
                <x-button type="button" variant="danger" onclick="removeField(${fieldCounter})" icon="bi bi-trash">Remove</x-button>

            </div>
        `;
        
        container.appendChild(fieldRow);
    }

    function removeField(id) {
        const field = document.getElementById(`dynamic-field-${id}`);
        if (field) {
            field.remove();
        }
    }
</script>

<!-- Extend Expiration Modal -->
@if($isAdminAgent && in_array($license->billing_status, ['paid', 'overridden']))
<div class="modal fade" id="extendExpirationModal" tabindex="-1" aria-labelledby="extendExpirationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.licenses.extend-expiration', $license) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="extendExpirationModalLabel">
                        <i class="bi bi-calendar-plus me-2"></i>Extend License Expiration
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Current Expiration:</strong> 
                        {{ $license->expiration_date ? $license->expiration_date->format('M d, Y') : 'Not set' }}
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_expiration_date" class="form-label fw-bold">New Expiration Date</label>
                        <input type="date" 
                               class="form-control" 
                               id="new_expiration_date" 
                               name="new_expiration_date" 
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               value="{{ $license->expiration_date ? $license->expiration_date->addYear()->format('Y-m-d') : now()->addYear()->format('Y-m-d') }}"
                               required>
                        <div class="form-text">Select the new expiration date for this license.</div>
                    </div>

                    <!-- Quick Select Buttons -->
                    <div class="mb-3">
                        <label class="form-label text-muted">Quick Select:</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <x-button type="button" variant="outline-secondary" size="sm" onclick="setExpirationDate(6)">+6 Months</x-button>
                            <x-button type="button" variant="outline-secondary" size="sm" onclick="setExpirationDate(12)">+1 Year</x-button>
                            <x-button type="button" variant="outline-secondary" size="sm" onclick="setExpirationDate(24)">+2 Years</x-button>
                            <x-button type="button" variant="outline-secondary" size="sm" onclick="setExpirationDate(36)">+3 Years</x-button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-button type="button" variant="secondary" data-bs-dismiss="modal">Cancel</x-button>
                    <x-button type="submit" variant="warning" icon="bi bi-check-lg">Extend License</x-button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setExpirationDate(months) {
        const currentDate = new Date('{{ $license->expiration_date ? $license->expiration_date->format("Y-m-d") : now()->format("Y-m-d") }}');
        const newDate = new Date(currentDate);
        newDate.setMonth(newDate.getMonth() + months);
        
        const formattedDate = newDate.toISOString().split('T')[0];
        document.getElementById('new_expiration_date').value = formattedDate;
    }
</script>
@endif
