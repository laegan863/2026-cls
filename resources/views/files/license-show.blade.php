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

    <!-- Renewal File Pending Alert -->
    @php
        $pendingRenewal = $license->pendingRenewal;
    @endphp
    @if($pendingRenewal && $isAdminAgent)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Renewal #{{ $pendingRenewal->renewal_number }} - File Required!</strong> 
                    Payment has been completed. Please upload the renewal evidence file to finalize the renewal and extend the expiration date to {{ $pendingRenewal->new_expiration_date->format('M d, Y') }}.
                </div>
                <x-button href="{{ route('admin.licenses.payments.show', $license) }}" variant="warning" size="sm" icon="bi bi-arrow-right">
                    Go to Payments
                </x-button>
            </div>
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
                        @if($isAdminAgent && in_array($license->billing_status, ['paid', 'overridden']) && !$license->license_document)
                            <x-button type="button" variant="success" icon="bi bi-upload" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                Upload Document
                            </x-button>
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
                    <label class="form-label fw-bold">DBA (Assumed Name)</label>
                    <p class="form-control-plaintext">{{ $license->dba ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">FEIN</label>
                    <p class="form-control-plaintext">{{ $license->fein ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Sales Tax ID</label>
                    <p class="form-control-plaintext">{{ $license->sales_tax_id ?? 'N/A' }}</p>
                </div>
            </div>
        </x-card>
    </div>

    <div class="my-2">
        <x-card title="Store Location" icon="bi bi-geo-alt-fill">
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <label class="form-label fw-bold">Street Number</label>
                    <p class="form-control-plaintext">{{ $license->street_number ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-9 mb-3">
                    <label class="form-label fw-bold">Street Name</label>
                    <p class="form-control-plaintext">{{ $license->street_name ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">City</label>
                    <p class="form-control-plaintext">{{ $license->city ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">County</label>
                    <p class="form-control-plaintext">{{ $license->county ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">State</label>
                    <p class="form-control-plaintext">{{ $license->state ?? 'N/A' }}</p>
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
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Jurisdiction Level</label>
                    <p class="form-control-plaintext">
                        @if($license->jurisdiction_level)
                            <x-badge type="info">{{ ucfirst($license->jurisdiction_level) }}</x-badge>
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Agency Name</label>
                    <p class="form-control-plaintext">{{ $license->agency_name ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">Permit Number</label>
                    <p class="form-control-plaintext">{{ $license->permit_number ?? $license->submission_confirmation_number ?? 'N/A' }}</p>
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
                    <label class="form-label fw-bold">Permit Number</label>
                    <p class="form-control-plaintext">{{ $license->permit_number ?? $license->submission_confirmation_number ?? 'N/A' }}</p>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-file-earmark-check me-1"></i>Renewal Evidence Document
                    </label>
                    <p class="form-control-plaintext">
                        @if($license->renewal_evidence_file)
                            <a href="{{ Storage::url($license->renewal_evidence_file) }}" 
                               target="_blank" 
                               class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download me-1"></i>Download Evidence File
                            </a>
                            <small class="text-muted d-block mt-1">{{ basename($license->renewal_evidence_file) }}</small>
                        @else
                            <span class="text-muted">No evidence file uploaded</span>
                        @endif
                    </p>
                </div>
            </div>
        </x-card>
    </div>

    <!-- License Document Card (Visible when document is uploaded) -->
    @if($license->license_document || ($isAdminAgent && in_array($license->billing_status, ['paid', 'overridden'])))
    <div class="my-2">
        <x-card title="License Document" icon="bi bi-file-earmark-pdf">
            @if($license->license_document)
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="bi bi-file-earmark-check text-success" style="font-size: 2rem;"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $license->license_document_name ?? 'License Document' }}</h6>
                                <small class="text-muted">
                                    Uploaded on {{ $license->license_document_uploaded_at ? $license->license_document_uploaded_at->format('M d, Y h:i A') : 'N/A' }}
                                    @if($license->documentUploader)
                                        by {{ $license->documentUploader->name }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ Storage::url($license->license_document) }}" 
                           target="_blank" 
                           class="btn btn-success">
                            <i class="bi bi-download me-1"></i>Download
                        </a>
                        @if($isAdminAgent)
                            <form action="{{ route('admin.licenses.delete-document', $license) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-trash me-1"></i>Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-file-earmark-x text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2 mb-0">No license document uploaded yet.</p>
                    @if($isAdminAgent)
                        <x-button type="button" variant="success" icon="bi bi-upload" class="mt-3" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                            Upload Document
                        </x-button>
                    @endif
                </div>
            @endif
        </x-card>
    </div>
    @endif

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

<!-- Upload Document Modal -->
@if($isAdminAgent && in_array($license->billing_status, ['paid', 'overridden']))
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.licenses.upload-document', $license) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadDocumentModalLabel">
                        <i class="bi bi-upload me-2"></i>Upload License Document
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Upload the official license document for this permit. The client will be able to view and download this document.
                    </div>
                    
                    @if($license->license_document)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> A document already exists. Uploading a new document will replace the current one.
                        <div class="mt-2">
                            <small><strong>Current file:</strong> {{ $license->license_document_name }}</small>
                        </div>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="license_document" class="form-label fw-bold">
                            <i class="bi bi-file-earmark-arrow-up me-1"></i>Select Document
                        </label>
                        <input type="file" 
                               class="form-control" 
                               id="license_document" 
                               name="license_document"
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                               required>
                        <div class="form-text">Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max: 10MB)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-button type="button" variant="secondary" data-bs-dismiss="modal">Cancel</x-button>
                    <x-button type="submit" variant="success" icon="bi bi-upload">Upload Document</x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
