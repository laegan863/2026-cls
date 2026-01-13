@extends('layouts.index')

@section('title', 'License Requirements')

@section('content')
    @php
        $role = Auth::user()->Role->name;
        $isAdminAgent = in_array($role, ['Admin', 'Agent']);
    @endphp

    <x-page-header title="License Requirements" subtitle="Transaction ID: {{ $license->transaction_id }}">
        <x-button href="{{ route('admin.licenses.show', $license) }}" variant="secondary" icon="bi bi-arrow-left">Back to License</x-button>
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

    <!-- Workflow Status Card -->
    <div class="row mb-4">
        <div class="col-12">
            <x-card>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Current Status</h5>
                        <x-badge type="{{ $license->workflow_status_badge }}">{{ $license->workflow_status_label }}</x-badge>
                    </div>
                    @if($isAdminAgent && $license->workflow_status !== 'rejected' && $license->workflow_status !== 'completed')
                        <div class="d-flex gap-2">
                            @if($license->allRequirementsApproved() && !$license->isApproved())
                                <form action="{{ route('admin.licenses.requirements.approve-license', $license) }}" method="POST" class="d-inline">
                                    @csrf
                                    <x-button type="submit" variant="success" icon="bi bi-check-circle">Approve License</x-button>
                                </form>
                            @endif
                            <x-button type="button" variant="danger" icon="bi bi-x-circle" data-bs-toggle="modal" data-bs-target="#rejectLicenseModal">Reject License</x-button>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>
    </div>

    <!-- Add Requirements Section (Admin/Agent Only) -->
    @if($isAdminAgent)
    <div class="row mb-4">
        <div class="col-12">
            <x-card title="Add New Requirements" icon="bi bi-plus-circle">
                <form action="{{ route('admin.licenses.requirements.store', $license) }}" method="POST" id="requirementsForm">
                    @csrf
                    
                    <div id="requirements-container">
                        <div class="row mb-3 requirement-row" data-index="0">
                            <div class="col-lg-4">
                                <label class="form-label">Requirement Label <span class="text-danger">*</span></label>
                                <x-input name="requirements[0][label]" type="text" placeholder="e.g., Business License Copy" required />
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Description</label>
                                <x-input name="requirements[0][description]" type="text" placeholder="Additional details about this requirement" />
                            </div>
                            <div class="col-lg-2 d-flex align-items-end">
                                <x-button type="button" variant="danger" onclick="removeRequirement(this)" icon="bi bi-trash">Remove</x-button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <x-button type="button" variant="secondary" onclick="addRequirement()" icon="bi bi-plus-lg">Add Another</x-button>
                        <x-button type="submit" variant="gold" icon="bi bi-send">Send Requirements to Client</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
    @endif

    <!-- Existing Requirements List -->
    <div class="row">
        <div class="col-12">
            <x-card title="Requirements List" icon="bi bi-list-check" :padding="false">
                @if($requirements->count() > 0)
                    <x-table>
                        <x-slot:head>
                            <tr>
                                <th>#</th>
                                <th>Requirement</th>
                                <th>Client Response</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </x-slot:head>
                        @foreach($requirements as $index => $requirement)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $requirement->label }}</strong>
                                    @if($requirement->description)
                                        <br><small class="text-muted">{{ $requirement->description }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($requirement->value)
                                        {{ $requirement->value }}
                                    @endif
                                    @if($requirement->file_path)
                                        <x-button href="{{ Storage::url($requirement->file_path) }}" target="_blank" variant="outline-primary" size="sm" icon="bi bi-file-earmark">View File</x-button>
                                    @endif
                                    @if(!$requirement->value && !$requirement->file_path)
                                        <span class="text-muted">Not submitted</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($requirement->status)
                                        @case('pending')
                                            <x-badge type="warning">Pending</x-badge>
                                            @break
                                        @case('submitted')
                                            <x-badge type="info">Submitted</x-badge>
                                            @break
                                        @case('approved')
                                            <x-badge type="success">Approved</x-badge>
                                            @break
                                        @case('rejected')
                                            <x-badge type="danger">Rejected</x-badge>
                                            @if($requirement->rejection_reason)
                                                <br><small class="text-danger">{{ $requirement->rejection_reason }}</small>
                                            @endif
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        {{-- Client can submit if pending or rejected --}}
                                        @if($role === 'Client' && in_array($requirement->status, ['pending', 'rejected']))
                                            <x-button type="button" variant="primary" size="sm" data-bs-toggle="modal" data-bs-target="#submitModal{{ $requirement->id }}">
                                                Submit
                                            </x-button>
                                        @endif

                                        {{-- Admin/Agent can approve/reject if submitted --}}
                                        @if($isAdminAgent && $requirement->status === 'submitted')
                                            <form action="{{ route('admin.licenses.requirements.approve', [$license, $requirement]) }}" method="POST" class="d-inline">
                                                @csrf
                                                <x-button type="submit" variant="success" size="sm" icon="bi bi-check">Approve</x-button>
                                            </form>
                                            <x-button type="button" variant="danger" size="sm" icon="bi bi-x" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $requirement->id }}">Reject</x-button>
                                        @endif

                                        {{-- Admin/Agent can delete requirement --}}
                                        @if($isAdminAgent)
                                            <form action="{{ route('admin.licenses.requirements.destroy', [$license, $requirement]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this requirement?')">
                                                @csrf
                                                @method('DELETE')
                                                <x-button type="submit" variant="outline-danger" size="sm" icon="bi bi-trash"></x-button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- Submit Modal (Client) --}}
                            @if($role === 'Client' && in_array($requirement->status, ['pending', 'rejected']))
                            <div class="modal fade" id="submitModal{{ $requirement->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.licenses.requirements.submit', [$license, $requirement]) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Submit: {{ $requirement->label }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if($requirement->description)
                                                    <p class="text-muted mb-3">{{ $requirement->description }}</p>
                                                @endif
                                                <div class="mb-3">
                                                    <label class="form-label">Your Response</label>
                                                    <x-textarea name="value" rows="3" placeholder="Enter your response..."></x-textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Upload File (optional)</label>
                                                    <input type="file" name="file" class="form-control" />
                                                    <small class="text-muted">Max file size: 10MB</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <x-button type="button" variant="secondary" data-bs-dismiss="modal">Cancel</x-button>
                                                <x-button type="submit" variant="gold">Submit</x-button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Reject Modal (Admin/Agent) --}}
                            @if($isAdminAgent && $requirement->status === 'submitted')
                            <div class="modal fade" id="rejectModal{{ $requirement->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.licenses.requirements.reject', [$license, $requirement]) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject: {{ $requirement->label }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                    <x-textarea name="rejection_reason" rows="3" placeholder="Explain why this requirement is being rejected..." required></x-textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <x-button type="button" variant="secondary" data-bs-dismiss="modal">Cancel</x-button>
                                                <x-button type="submit" variant="danger">Reject</x-button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </x-table>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                        <p class="text-muted">No requirements have been added yet.</p>
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    {{-- Reject License Modal --}}
    @if($isAdminAgent)
    <div class="modal fade" id="rejectLicenseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.licenses.requirements.reject-license', $license) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject License Application</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> This action will reject the entire license application.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <x-textarea name="rejection_reason" rows="4" placeholder="Explain why this license is being rejected..." required></x-textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-button type="button" variant="secondary" data-bs-dismiss="modal">Cancel</x-button>
                        <x-button type="submit" variant="danger">Reject License</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script>
let requirementIndex = 0;

function addRequirement() {
    requirementIndex++;
    const container = document.getElementById('requirements-container');
    
    const row = document.createElement('div');
    row.className = 'row mb-3 requirement-row';
    row.setAttribute('data-index', requirementIndex);
    
    row.innerHTML = `
        <div class="col-lg-4">
            <label class="form-label">Requirement Label <span class="text-danger">*</span></label>
            <input type="text" name="requirements[${requirementIndex}][label]" class="form-control" placeholder="e.g., Business License Copy" required />
        </div>
        <div class="col-lg-6">
            <label class="form-label">Description</label>
            <input type="text" name="requirements[${requirementIndex}][description]" class="form-control" placeholder="Additional details about this requirement" />
        </div>
        <div class="col-lg-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger-custom" onclick="removeRequirement(this)">
                <i class="bi bi-trash"></i> Remove
            </button>
        </div>
    `;
    
    container.appendChild(row);
}

function removeRequirement(button) {
    const rows = document.querySelectorAll('.requirement-row');
    if (rows.length > 1) {
        button.closest('.requirement-row').remove();
    } else {
        alert('At least one requirement is required.');
    }
}
</script>
@endpush
