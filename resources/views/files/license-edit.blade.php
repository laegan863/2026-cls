@extends('layouts.index')

@section('title', 'Edit License')

@section('content')
    <x-page-header title="Edit License" subtitle="Editing license for {{ $license->legal_name ?? 'N/A' }}">
        <x-button href="{{ route('admin.licenses.index') }}" variant="primary" icon="bi bi-arrow-left">Back</x-button>
    </x-page-header>

    @php
        $role = Auth::user()->Role->name;
    @endphp

    <form action="{{ route('admin.licenses.update', $license) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="my-2">
            <x-card title="Client Information" icon="bi bi-person-fill">
                <div class="form-group">
                    <div class="row">
                        @if($role == 'Admin')
                            {{-- Admin sees dropdown to select client --}}
                            <div class="col-lg-6 mb-3">
                                <label for="client_id" class="form-label">Client Name</label>
                                <x-select name="client_id" placeholder="Select Client Name">
                                    @php
                                        $users = \App\Models\User::where('is_active', 1)
                                            ->whereHas('Role', function ($query) {
                                                $query->where('name', 'Client');
                                            })->get();
                                    @endphp
                                    @forelse($users as $user)
                                        <option value="{{ $user->id }}" {{ $license->client_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @empty
                                        <option disabled>No active clients found</option>
                                    @endforelse
                                </x-select>
                            </div>
                        @else
                            {{-- Client sees their own name (read-only) --}}
                            <div class="col-lg-6 mb-3">
                                <label for="client_name" class="form-label">Client Name</label>
                                <x-input name="client_name" type="text" value="{{ Auth::user()->name }}" readonly />
                                <input type="hidden" name="client_id" value="{{ Auth::id() }}">
                            </div>
                        @endif
                        
                        <div class="col-lg-6 mb-3">
                            <label for="email" class="form-label">Billing email(s)</label>
                            <x-input name="email" type="email" placeholder="Enter billing email(s)" value="{{ old('email', $license->email) }}" />
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="primary_contact_info" class="form-label">Primary contact info</label>
                            <x-input name="primary_contact_info" type="text" placeholder="Enter primary contact info" value="{{ old('primary_contact_info', $license->primary_contact_info) }}" />
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="my-2">
            <x-card title="Business Entity" icon="bi bi-building">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="legal_name" class="form-label">Legal Name</label>
                            <x-input name="legal_name" type="text" placeholder="Enter legal name" value="{{ old('legal_name', $license->legal_name) }}" />
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="dba" class="form-label">DBA</label>
                            <x-input name="dba" type="text" placeholder="Enter DBA" value="{{ old('dba', $license->dba) }}" />
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="fein" class="form-label">FEIN</label>
                            <x-input name="fein" type="text" placeholder="Enter FEIN" value="{{ old('fein', $license->fein) }}" />
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="my-2">
            <x-card title="Store / Location (Primary Operating Unit)" icon="bi bi-geo-alt-fill">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select name="country" id="country" class="form-select" data-selected="{{ $license->country }}">
                                <option value="">Select Country</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="state" class="form-label">State</label>
                            <select name="state" id="state" class="form-select" data-selected="{{ $license->state }}">
                                <option value="">Select State</option>
                                @if($license->state)
                                    <option value="{{ $license->state }}" selected>{{ $license->state }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <select name="city" id="city" class="form-select" data-selected="{{ $license->city }}">
                                <option value="">Select City</option>
                                @if($license->city)
                                    <option value="{{ $license->city }}" selected>{{ $license->city }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="zip_code" class="form-label">Zip Code</label>
                            <x-input name="zip_code" type="text" placeholder="Enter zip code" value="{{ old('zip_code', $license->zip_code) }}" />
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="my-2">
            <x-card title="Permit / License Details" icon="bi bi-file-earmark-text-fill">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="permit_type" class="form-label">Permit Type</label>
                            <x-select name="permit_type" placeholder="Select Permit Type">
                                <option value="business_license" {{ $license->permit_type == 'business_license' ? 'selected' : '' }}>Business License</option>
                                <option value="health_permit" {{ $license->permit_type == 'health_permit' ? 'selected' : '' }}>Health Permit</option>
                                <option value="fire_permit" {{ $license->permit_type == 'fire_permit' ? 'selected' : '' }}>Fire Permit</option>
                                <option value="zoning_permit" {{ $license->permit_type == 'zoning_permit' ? 'selected' : '' }}>Zoning Permit</option>
                                <option value="building_permit" {{ $license->permit_type == 'building_permit' ? 'selected' : '' }}>Building Permit</option>
                                <option value="liquor_license" {{ $license->permit_type == 'liquor_license' ? 'selected' : '' }}>Liquor License</option>
                                <option value="food_service" {{ $license->permit_type == 'food_service' ? 'selected' : '' }}>Food Service License</option>
                                <option value="other" {{ $license->permit_type == 'other' ? 'selected' : '' }}>Other</option>
                            </x-select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="permit_subtype" class="form-label">Permit Subtype</label>
                            <x-select name="permit_subtype" placeholder="Select Permit Subtype">
                                <option value="new" {{ $license->permit_subtype == 'new' ? 'selected' : '' }}>New</option>
                                <option value="renewal" {{ $license->permit_subtype == 'renewal' ? 'selected' : '' }}>Renewal</option>
                                <option value="amendment" {{ $license->permit_subtype == 'amendment' ? 'selected' : '' }}>Amendment</option>
                                <option value="transfer" {{ $license->permit_subtype == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            </x-select>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="my-2">
            <x-card title="Jurisdiction" icon="bi bi-globe">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="jurisdiction_country" class="form-label">Jurisdiction Country</label>
                            <select name="jurisdiction_country" class="form-select" id="jurisdiction_country" data-selected="{{ $license->jurisdiction_country }}">
                                <option value="">Select Country</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="jurisdiction_state" class="form-label">Jurisdiction State</label>
                            <select name="jurisdiction_state" class="form-select" id="jurisdiction_state" data-selected="{{ $license->jurisdiction_state }}">
                                <option value="">Select State</option>
                                @if($license->jurisdiction_state)
                                    <option value="{{ $license->jurisdiction_state }}" selected>{{ $license->jurisdiction_state }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="jurisdiction_city" class="form-label">Jurisdiction City</label>
                            <select name="jurisdiction_city" class="form-select" id="jurisdiction_city" data-selected="{{ $license->jurisdiction_city }}">
                                <option value="">Select City</option>
                                @if($license->jurisdiction_city)
                                    <option value="{{ $license->jurisdiction_city }}" selected>{{ $license->jurisdiction_city }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="jurisdiction_federal" class="form-label">Federal</label>
                            <x-input name="jurisdiction_federal" type="text" placeholder="Enter federal jurisdiction" value="{{ old('jurisdiction_federal', $license->jurisdiction_federal) }}" />
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="agency_name" class="form-label">Agency Name</label>
                            @php
                                $agencies = \App\Models\Agency::where('is_active', true)->orderBy('name')->get();
                            @endphp
                            <x-select name="agency_name" placeholder="Select Agency">
                                @forelse ($agencies as $agency)
                                    <option value="{{ $agency->name }}" {{ old('agency_name', $license->agency_name) == $agency->name ? 'selected' : '' }}>{{ $agency->name }}</option>
                                @empty
                                    <option value="" disabled>No agencies available</option>
                                @endforelse
                            </x-select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="expiration_date" class="form-label">Expiration Date</label>
                            <x-input name="expiration_date" type="date" value="{{ old('expiration_date', $license->expiration_date ? $license->expiration_date->format('Y-m-d') : '') }}" />
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="renewal_window_open_date" class="form-label">Renewal Window Open Date</label>
                            <x-input name="renewal_window_open_date" type="date" value="{{ old('renewal_window_open_date', $license->renewal_window_open_date ? $license->renewal_window_open_date->format('Y-m-d') : '') }}" />
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="renewal_status" class="form-label">Renewal Status</label>
                            <x-select name="renewal_status" placeholder="Select Renewal Status">
                                <option value="monitoring" {{ $license->renewal_status == 'monitoring' ? 'selected' : '' }}>Monitoring (Active)</option>
                                <option value="billing_window_open" {{ $license->renewal_status == 'billing_window_open' ? 'selected' : '' }}>Billing Window Open</option>
                                <option value="pending_payment" {{ $license->renewal_status == 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                                <option value="ready_to_submit" {{ $license->renewal_status == 'ready_to_submit' ? 'selected' : '' }}>Ready to Submit</option>
                                <option value="submitted" {{ $license->renewal_status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="waiting_agency_response" {{ $license->renewal_status == 'waiting_agency_response' ? 'selected' : '' }}>Waiting Agency Response</option>
                                <option value="additional_info_requested" {{ $license->renewal_status == 'additional_info_requested' ? 'selected' : '' }}>Additional Info Requested</option>
                                <option value="pending_client_response" {{ $license->renewal_status == 'pending_client_response' ? 'selected' : '' }}>Pending Client Response</option>
                                <option value="resubmitted" {{ $license->renewal_status == 'resubmitted' ? 'selected' : '' }}>Resubmitted</option>
                                <option value="approved_completed" {{ $license->renewal_status == 'approved_completed' ? 'selected' : '' }}>Approved / Completed</option>
                                <option value="failed_closed" {{ $license->renewal_status == 'failed_closed' ? 'selected' : '' }}>Failed / Closed</option>
                            </x-select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="billing_status" class="form-label">Billing Status</label>
                            <x-select name="billing_status" placeholder="Select Billing Status">
                                <option value="not_invoiced" {{ $license->billing_status == 'not_invoiced' ? 'selected' : '' }}>Not Invoiced</option>
                                <option value="invoiced" {{ $license->billing_status == 'invoiced' ? 'selected' : '' }}>Invoiced</option>
                                <option value="payment_pending" {{ $license->billing_status == 'payment_pending' ? 'selected' : '' }}>Payment Pending</option>
                                <option value="paid_online" {{ $license->billing_status == 'paid_online' ? 'selected' : '' }}>Paid (Online)</option>
                                <option value="paid_offline" {{ $license->billing_status == 'paid_offline' ? 'selected' : '' }}>Paid (Offline)</option>
                                <option value="override_approved" {{ $license->billing_status == 'override_approved' ? 'selected' : '' }}>Override Approved</option>
                                <option value="voided" {{ $license->billing_status == 'voided' ? 'selected' : '' }}>Voided</option>
                            </x-select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="submission_confirmation_number">Submission Confirmation Number</label>
                            <x-input type="text" name="submission_confirmation_number" placeholder="Enter Submission Confirmation Number" value="{{ old('submission_confirmation_number', $license->submission_confirmation_number) }}" />
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="my-3 d-flex justify-content-end gap-2">
            <x-button href="{{ route('admin.licenses.index') }}" variant="primary" icon="bi bi-arrow-left">Cancel</x-button>
            <x-button type="submit" variant="gold" icon="bi bi-save">Update License</x-button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_BASE = 'https://api.countrystatecity.in/v1';
    const API_KEY = 'NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA==';

    // Store / Location dropdowns
    const countrySelect = document.getElementById('country');
    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');

    // Jurisdiction dropdowns
    const jurisdictionCountrySelect = document.getElementById('jurisdiction_country');
    const jurisdictionStateSelect = document.getElementById('jurisdiction_state');
    const jurisdictionCitySelect = document.getElementById('jurisdiction_city');

    // Load Countries for both dropdowns
    fetch(`${API_BASE}/countries`, {
        headers: { 'X-CSCAPI-KEY': API_KEY }
    })
    .then(response => response.json())
    .then(data => {
        let options = '<option value="">Select Country</option>';
        data.forEach(country => {
            options += `<option value="${country.iso2}" data-name="${country.name}">${country.name}</option>`;
        });
        countrySelect.innerHTML = options;
        jurisdictionCountrySelect.innerHTML = options;
        
        // Set selected country if exists
        const selectedCountry = countrySelect.dataset.selected;
        if (selectedCountry) {
            // Find and select the country option
            for (let option of countrySelect.options) {
                if (option.value === selectedCountry || option.text === selectedCountry) {
                    option.selected = true;
                    // Trigger change to load states
                    countrySelect.dispatchEvent(new Event('change'));
                    break;
                }
            }
        }
        
        const selectedJurisdictionCountry = jurisdictionCountrySelect.dataset.selected;
        if (selectedJurisdictionCountry) {
            for (let option of jurisdictionCountrySelect.options) {
                if (option.value === selectedJurisdictionCountry || option.text === selectedJurisdictionCountry) {
                    option.selected = true;
                    jurisdictionCountrySelect.dispatchEvent(new Event('change'));
                    break;
                }
            }
        }
    });

    // ===== Store / Location handlers =====
    countrySelect.addEventListener('change', function() {
        const countryCode = this.value;
        const savedState = stateSelect.dataset.selected;
        const savedCity = citySelect.dataset.selected;
        
        stateSelect.innerHTML = '<option value="">Select State</option>';
        stateSelect.disabled = true;
        citySelect.innerHTML = '<option value="">Select City</option>';
        citySelect.disabled = true;

        if (countryCode) {
            fetch(`${API_BASE}/countries/${countryCode}/states`, {
                headers: { 'X-CSCAPI-KEY': API_KEY }
            })
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Select State</option>';
                data.forEach(state => {
                    const isSelected = (state.iso2 === savedState || state.name === savedState) ? 'selected' : '';
                    options += `<option value="${state.iso2}" data-name="${state.name}" ${isSelected}>${state.name}</option>`;
                });
                stateSelect.innerHTML = options;
                stateSelect.disabled = false;
                
                // If state was pre-selected, trigger change to load cities
                if (savedState) {
                    stateSelect.dispatchEvent(new Event('change'));
                }
            });
        }
    });

    stateSelect.addEventListener('change', function() {
        const countryCode = countrySelect.value;
        const stateCode = this.value;
        const savedCity = citySelect.dataset.selected;
        
        citySelect.innerHTML = '<option value="">Select City</option>';
        citySelect.disabled = true;

        if (countryCode && stateCode) {
            fetch(`${API_BASE}/countries/${countryCode}/states/${stateCode}/cities`, {
                headers: { 'X-CSCAPI-KEY': API_KEY }
            })
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Select City</option>';
                data.forEach(city => {
                    const isSelected = city.name === savedCity ? 'selected' : '';
                    options += `<option value="${city.name}" ${isSelected}>${city.name}</option>`;
                });
                citySelect.innerHTML = options;
                citySelect.disabled = false;
            });
        }
    });

    // ===== Jurisdiction handlers =====
    jurisdictionCountrySelect.addEventListener('change', function() {
        const countryCode = this.value;
        const savedState = jurisdictionStateSelect.dataset.selected;
        const savedCity = jurisdictionCitySelect.dataset.selected;
        
        jurisdictionStateSelect.innerHTML = '<option value="">Select State</option>';
        jurisdictionStateSelect.disabled = true;
        jurisdictionCitySelect.innerHTML = '<option value="">Select City</option>';
        jurisdictionCitySelect.disabled = true;

        if (countryCode) {
            fetch(`${API_BASE}/countries/${countryCode}/states`, {
                headers: { 'X-CSCAPI-KEY': API_KEY }
            })
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Select State</option>';
                data.forEach(state => {
                    const isSelected = (state.iso2 === savedState || state.name === savedState) ? 'selected' : '';
                    options += `<option value="${state.iso2}" data-name="${state.name}" ${isSelected}>${state.name}</option>`;
                });
                jurisdictionStateSelect.innerHTML = options;
                jurisdictionStateSelect.disabled = false;
                
                if (savedState) {
                    jurisdictionStateSelect.dispatchEvent(new Event('change'));
                }
            });
        }
    });

    jurisdictionStateSelect.addEventListener('change', function() {
        const countryCode = jurisdictionCountrySelect.value;
        const stateCode = this.value;
        const savedCity = jurisdictionCitySelect.dataset.selected;
        
        jurisdictionCitySelect.innerHTML = '<option value="">Select City</option>';
        jurisdictionCitySelect.disabled = true;

        if (countryCode && stateCode) {
            fetch(`${API_BASE}/countries/${countryCode}/states/${stateCode}/cities`, {
                headers: { 'X-CSCAPI-KEY': API_KEY }
            })
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Select City</option>';
                data.forEach(city => {
                    const isSelected = city.name === savedCity ? 'selected' : '';
                    options += `<option value="${city.name}" ${isSelected}>${city.name}</option>`;
                });
                jurisdictionCitySelect.innerHTML = options;
                jurisdictionCitySelect.disabled = false;
            });
        }
    });
});
</script>
@endpush
