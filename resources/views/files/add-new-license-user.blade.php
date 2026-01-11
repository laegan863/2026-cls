@extends('layouts.index')

@section('title', 'Licensing & Permitting')

@section('content')
    <x-page-header title="Add New Licensing & Permitting" subtitle="Welcome back, John! Here's what's happening today.">
        <x-button href="{{ url()->previous() }}" variant="gold" icon="bi bi-arrow-left">back</x-button>
    </x-page-header>

    @php
        $role = Auth::user()->Role->name;
    @endphp

    <form action="{{ route('licenses.store') }}" method="POST">
        @csrf
        
    <div class="my-2">
            <x-card title="Client Information" icon="bi bi-file-earmark-text-fill">
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
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
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
                            <x-input name="email" type="email" placeholder="Enter billing email(s)" />
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="primary_contact_info" class="form-label">Primary contact info</label>
                            <x-input name="primary_contact_info" type="text" placeholder="Enter primary contact info" />
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="my-2">
            <x-card title="Business Entity" icon="bi bi-file-earmark-text-fill">
                <div class="form-group">
                    @php
                        $client = [
                            [
                                'label' => 'Legal name',
                                'name' => 'legal_name',
                            ],
                            [
                                'label' => 'DBA',
                                'name' => 'dba',
                            ],
                            [
                                'label' => 'FEIN',
                                'name' => 'fein',
                            ],
                        ];
                    @endphp
                    <div class="row">
                        @foreach ($client as $field)
                            <div class="col-lg-6 mb-3">
                                <label for="{{ $field['name'] }}" class="form-label">{{ $field['label'] }}</label>
                                <x-input name="{{ $field['name'] }}" type="text"
                                    placeholder="Enter {{ strtolower($field['label']) }}" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-card>
        </div>

    <div class="my-2">
        <x-card title="Store / Location (Primary Operating Unit)" icon="bi bi-file-earmark-text-fill">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="country" class="form-label">Country</label>
                        <select name="country" id="country" class="form-select">
                            <option value="">Select Country</option>
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="state" class="form-label">State</label>
                        <select name="state" id="state" class="form-select" disabled>
                            <option value="">Select State</option>
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="city" class="form-label">City</label>
                        <select name="city" id="city" class="form-select" disabled>
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="zip_code" class="form-label">Zip Code</label>
                        <x-input name="zip_code" type="text" placeholder="Enter zip code" />
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
                        <label for="permit_type" class="form-label">Permit Type/Sub Type</label>
                        @php
                            $permit_types = \App\Models\PermitType::where('is_active', 1)->get();
                        @endphp
                        <x-select name="permit_type" placeholder="Select Permit Type">
                            @forelse ($permit_types as $type)
                                <option value="{{ $type->permit_type }}">{{ $type->permit_type }}</option>
                            @empty
                                <option value="" disabled>No Permit Type Available</option>
                            @endforelse
                        </x-select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="permit_subtype" class="form-label">Permit Subtype</label>
                        <x-select name="permit_subtype" placeholder="Select Permit Subtype">
                            <option value="new">New</option>
                            <option value="renewal">Renewal</option>
                            <option value="amendment">Amendment</option>
                            <option value="transfer">Transfer</option>
                        </x-select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="jurisdiction_country" class="form-label">Jurisdiction Country</label>
                        <select name="jurisdiction_country" class="form-select" id="jurisdiction_country">
                            <option value="">Select Country</option>
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="jurisdiction_state" class="form-label">Jurisdiction State</label>
                        <select name="jurisdiction_state" class="form-select" id="jurisdiction_state" disabled>
                            <option value="">Select State</option>
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="jurisdiction_city" class="form-label">Jurisdiction City</label>
                        <select name="jurisdiction_city" class="form-select" id="jurisdiction_city" disabled>
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="jurisdiction_federal" class="form-label">Federal</label>
                        <x-input name="jurisdiction_federal" type="text" placeholder="Enter federal jurisdiction" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="agency_name" class="form-label">Agency Name</label>
                        <x-input name="agency_name" type="text" placeholder="Enter agency name" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="expiration_date" class="form-label">Expiration Date</label>
                        <x-input name="expiration_date" type="date" placeholder="Select expiration date" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="renewal_window_open_date" class="form-label">Renewal Window Open Date</label>
                        <x-input name="renewal_window_open_date" type="date" placeholder="Select renewal window open date" />
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="assigned_agent" class="form-label">Assigned Agent</label>
                        <x-select name="assigned_agent" placeholder="Select Assigned Agent">
                            <option value="agent_1">Agent 1</option>
                            <option value="agent_2">Agent 2</option>
                            <option value="agent_3">Agent 3</option>
                        </x-select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="renewal_status" class="form-label">Renewal Status</label>
                        <x-select name="renewal_status" placeholder="Select Renewal Status">
                            <option value="monitoring">Monitoring (Active)</option>
                            <option value="billing_window_open">Billing Window Open</option>
                            <option value="pending_payment">Pending Payment</option>
                            <option value="ready_to_submit">Ready to Submit</option>
                            <option value="submitted">Submitted</option>
                            <option value="waiting_agency_response">Waiting Agency Response</option>
                            <option value="additional_info_requested">Additional Info Requested</option>
                            <option value="pending_client_response">Pending Client Response</option>
                            <option value="resubmitted">Resubmitted</option>
                            <option value="approved_completed">Approved / Completed</option>
                            <option value="failed_closed">Failed / Closed</option>
                        </x-select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="billing_status" class="form-label">Billing Status</label>
                        <x-select name="billing_status" placeholder="Select Billing Status">
                            <option value="not_invoiced">Not Invoiced</option>
                            <option value="invoiced">Invoiced</option>
                            <option value="payment_pending">Payment Pending</option>
                            <option value="paid_online">Paid (Online)</option>
                            <option value="paid_offline">Paid (Offline)</option>
                            <option value="override_approved">Override Approved</option>
                            <option value="voided">Voided</option>
                        </x-select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label" for="submission_confirmation_number">Submission Confirmation Number</label>
                        <x-input type="text" name="submission_confirmation_number" placeholder="Enter Submission Confirmation Number" />
                    </div>
                </div>
                
            </div>
        </x-card>
    </div>

    <div class="my-3 d-flex justify-content-end gap-2">
        <x-button href="{{ route('licenses.index') }}" variant="secondary">Cancel</x-button>
        <x-button type="submit" variant="gold" icon="bi bi-save">Save License</x-button>
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
    });

    // ===== Store / Location handlers =====
    countrySelect.addEventListener('change', function() {
        const countryCode = this.value;
        
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
                    options += `<option value="${state.iso2}" data-name="${state.name}">${state.name}</option>`;
                });
                stateSelect.innerHTML = options;
                stateSelect.disabled = false;
            });
        }
    });

    stateSelect.addEventListener('change', function() {
        const countryCode = countrySelect.value;
        const stateCode = this.value;
        
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
                    options += `<option value="${city.name}">${city.name}</option>`;
                });
                citySelect.innerHTML = options;
                citySelect.disabled = false;
            });
        }
    });

    // ===== Jurisdiction handlers =====
    jurisdictionCountrySelect.addEventListener('change', function() {
        const countryCode = this.value;
        
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
                    options += `<option value="${state.iso2}" data-name="${state.name}">${state.name}</option>`;
                });
                jurisdictionStateSelect.innerHTML = options;
                jurisdictionStateSelect.disabled = false;
            });
        }
    });

    jurisdictionStateSelect.addEventListener('change', function() {
        const countryCode = jurisdictionCountrySelect.value;
        const stateCode = this.value;
        
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
                    options += `<option value="${city.name}">${city.name}</option>`;
                });
                jurisdictionCitySelect.innerHTML = options;
                jurisdictionCitySelect.disabled = false;
            });
        }
    });
});
</script>
@endpush
