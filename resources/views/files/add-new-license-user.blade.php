@extends('layouts.index')

@section('title', 'Licensing & Permitting')

@section('content')
    <x-page-header title="Add New Licensing & Permitting" subtitle="Welcome back, John! Here's what's happening today.">
        <x-button href="{{ url()->previous() }}" variant="gold" icon="bi bi-arrow-left">back</x-button>
    </x-page-header>

    {{ Auth::user()->Role->name }}
    @if(Auth::user()->Role->name == 'Admin')
        <div class="my-2">
            <x-card title="Client Information" icon="bi bi-file-earmark-text-fill">
                <div class="form-group">
                    @php
                        $client = [
                            [
                                'label' => 'Client Name',
                                'name' => 'name',
                            ],
                            [
                                'label' => 'Billing email(s)',
                                'name' => 'email',
                            ],
                            [
                                'label' => 'Primary contact info',
                                'name' => 'primary_contact_info',
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
    @endif

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
                @php
                    $client = [
                        [
                            'label' => 'Country',
                            'name' => 'country',
                        ],
                        [
                            'label' => 'City',
                            'name' => 'city',
                        ],
                        [
                            'label' => 'State',
                            'name' => 'state',
                        ],
                        [
                            'label' => 'Zip Code',
                            'name' => 'zip_code',
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
        <x-card title="Permit / License Details" icon="bi bi-file-earmark-text-fill">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="permit_type" class="form-label">Permit Type</label>
                        <x-select name="permit_type" placeholder="Select Permit Type">
                            <option value="business_license">Business License</option>
                            <option value="health_permit">Health Permit</option>
                            <option value="fire_permit">Fire Permit</option>
                            <option value="zoning_permit">Zoning Permit</option>
                            <option value="building_permit">Building Permit</option>
                            <option value="liquor_license">Liquor License</option>
                            <option value="food_service">Food Service License</option>
                            <option value="other">Other</option>
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
                        <label for="jurisdiction" class="form-label">Jurisdiction</label>
                        <x-select name="jurisdiction" placeholder="Select Jurisdiction">
                            <option value="city">City</option>
                            <option value="county">County</option>
                            <option value="state">State</option>
                            <option value="federal">Federal</option>
                        </x-select>
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

@endsection
