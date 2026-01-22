@extends('layouts.index')

@section('title', 'Create Permit Type')

@section('content')
    <x-page-header title="Create Permit Type" subtitle="Add a new permit type to the system.">
        <x-button href="{{ route('admin.permit-types.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Permit Types</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-8">
            <x-card title="Permit Type Information" icon="bi bi-file-earmark-plus">
                <form action="{{ route('admin.permit-types.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="permit_type" class="form-label">Permit Type Name <span class="text-danger">*</span></label>
                        <x-input name="permit_type" placeholder="Enter permit type name" value="{{ old('permit_type') }}" required />
                        @error('permit_type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <x-checkbox name="is_active" value="1" :checked="old('is_active', true)" label="Active" />
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="bi bi-diagram-3"></i> Sub-Permits (Optional)</h6>
                            <x-button type="button" variant="outline-primary" size="sm" icon="bi bi-plus-lg" onclick="addSubPermit()">Add Sub-Permit</x-button>
                        </div>
                        <p class="text-muted small">Add optional sub-permits under this permit type.</p>
                        
                        <div id="sub-permits-container">
                            <!-- Dynamic sub-permits will be added here -->
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <x-button type="submit" variant="gold" icon="bi bi-check-lg">Create Permit Type</x-button>
                        <x-button href="{{ route('admin.permit-types.index') }}" variant="outline">Cancel</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
<script>
let subPermitCounter = 0;

function addSubPermit(name = '', isActive = true) {
    const container = document.getElementById('sub-permits-container');
    const index = subPermitCounter++;
    
    const html = `
        <div class="card mb-2" id="sub-permit-${index}">
            <div class="card-body py-2 px-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <input type="text" class="form-control form-control-sm" 
                               name="sub_permits[${index}][name]" 
                               placeholder="Sub-permit name" 
                               value="${name}" required>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" 
                                   name="sub_permits[${index}][is_active]" 
                                   id="sub_permit_active_${index}" 
                                   value="1" ${isActive ? 'checked' : ''}>
                            <label class="form-check-label" for="sub_permit_active_${index}">Active</label>
                        </div>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSubPermit(${index})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
}

function removeSubPermit(index) {
    const element = document.getElementById(`sub-permit-${index}`);
    if (element) {
        element.remove();
    }
}
</script>
@endpush
