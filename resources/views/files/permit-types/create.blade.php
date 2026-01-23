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
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="permit_type" class="form-label">Permit Type Name <span class="text-danger">*</span></label>
                            <x-input name="permit_type" placeholder="Enter permit type name" value="{{ old('permit_type') }}" required />
                            @error('permit_type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="short_name" class="form-label">Short Name</label>
                            <x-input name="short_name" placeholder="Enter short name (optional)" value="{{ old('short_name') }}" />
                            @error('short_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <x-textarea name="description" rows="3" placeholder="Enter description (visible to client)" required />
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <!-- Jurisdiction Section -->
                    <div class="mb-4">
                        <h6 class="mb-3"><i class="bi bi-globe"></i> Jurisdiction</h6>
                        
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="jurisdiction_level" class="form-label">Jurisdiction Level</label>
                                <x-select name="jurisdiction_level" id="jurisdiction_level">
                                    <option value="">Select Jurisdiction Level</option>
                                    <option value="city" {{ old('jurisdiction_level') == 'city' ? 'selected' : '' }}>City</option>
                                    <option value="county" {{ old('jurisdiction_level') == 'county' ? 'selected' : '' }}>County</option>
                                    <option value="state" {{ old('jurisdiction_level') == 'state' ? 'selected' : '' }}>State</option>
                                    <option value="federal" {{ old('jurisdiction_level') == 'federal' ? 'selected' : '' }}>Federal</option>
                                </x-select>
                                @error('jurisdiction_level')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="agency_name" class="form-label">Agency Name</label>
                                @php
                                    $agencies = \App\Models\Agency::where('is_active', true)->orderBy('name')->get();
                                @endphp
                                <x-select name="agency_name">
                                    <option value="">Select Agency</option>
                                    @forelse ($agencies as $agency)
                                        <option value="{{ $agency->name }}" {{ old('agency_name') == $agency->name ? 'selected' : '' }}>{{ $agency->name }}</option>
                                    @empty
                                        <option value="" disabled>No agencies available</option>
                                    @endforelse
                                </x-select>
                                @error('agency_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-checkbox name="is_active" value="1" :checked="old('is_active', true)" label="Active" />
                    </div>

                    <hr class="my-4">

                    <!-- Renewal Settings Section -->
                    <div class="mb-4">
                        <h6 class="mb-3"><i class="bi bi-arrow-repeat"></i> Renewal Settings</h6>
                        
                        <div class="mb-3">
                            <x-toggle name="has_renewal" label="Enable Renewal?" :checked="old('has_renewal')" onchange="toggleRenewalSettings()" />
                        </div>

                        <div id="renewal-settings" style="{{ old('has_renewal') ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="renewal_cycle_months" class="form-label">Renewal Cycle (Months)</label>
                                    <x-input type="number" name="renewal_cycle_months" placeholder="e.g., 24 for 2 years" value="{{ old('renewal_cycle_months') }}" min="1" />
                                    <small class="text-muted">How often this permit needs to be renewed</small>
                                    @error('renewal_cycle_months')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Reminder Emails</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        <x-checkbox name="reminder_days[]" value="60" :checked="is_array(old('reminder_days')) && in_array('60', old('reminder_days'))" label="60 Days Before" />
                                        <x-checkbox name="reminder_days[]" value="30" :checked="is_array(old('reminder_days')) && in_array('30', old('reminder_days'))" label="30 Days Before" />
                                        <x-checkbox name="reminder_days[]" value="15" :checked="is_array(old('reminder_days')) && in_array('15', old('reminder_days'))" label="15 Days Before" />
                                    </div>
                                    <small class="text-muted">Select when to send reminder emails before expiration</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Fees Associated Section -->
                    <div class="mb-4">
                        <h6 class="mb-3"><i class="bi bi-currency-dollar"></i> Fees Associated</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="government_fee" class="form-label">Government Fee</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="government_fee" id="government_fee" step="0.01" min="0" placeholder="0.00" value="{{ old('government_fee') }}">
                                </div>
                                <small class="text-muted">Automatically appears on invoice</small>
                                @error('government_fee')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cls_service_fee" class="form-label">CLS-360 Service Fee</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="cls_service_fee" id="cls_service_fee" step="0.01" min="0" placeholder="0.00" value="{{ old('cls_service_fee') }}">
                                </div>
                                <small class="text-muted">Automatically appears on invoice</small>
                                @error('cls_service_fee')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city_county_fee" class="form-label">City/County Fee</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="city_county_fee" id="city_county_fee" step="0.01" min="0" placeholder="0.00" value="{{ old('city_county_fee') }}">
                                </div>
                                <small class="text-muted">Automatically appears on invoice</small>
                                @error('city_county_fee')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="additional_fee" class="form-label">Additional Fee</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="additional_fee" id="additional_fee" step="0.01" min="0" placeholder="0.00" value="{{ old('additional_fee') }}">
                                </div>
                                @error('additional_fee')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="additional_fee_description" class="form-label">Additional Fee Description</label>
                            <x-input name="additional_fee_description" placeholder="Describe the additional fee (optional)" value="{{ old('additional_fee_description') }}" />
                            @error('additional_fee_description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
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
function toggleRenewalSettings() {
    const checkbox = document.getElementById('has_renewal');
    const settings = document.getElementById('renewal-settings');
    settings.style.display = checkbox.checked ? 'block' : 'none';
}

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
