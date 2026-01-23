@extends('layouts.index')

@section('title', 'Licensing & Permitting')

@section('content')
    <x-page-header title="Add New Licensing & Permitting" subtitle="Welcome back, John! Here's what's happening today.">
        <x-button href="{{ url()->previous() }}" variant="gold" icon="bi bi-arrow-left">back</x-button>
    </x-page-header>

    @php
        $role = Auth::user()->Role->name;
    @endphp

    <form action="{{ route('admin.licenses.store') }}" method="POST" enctype="multipart/form-data" id="license-form">
        @csrf
        <input type="hidden" name="payment_action" id="payment-action" value="stripe">
        
    <div class="my-2">
            <x-card title="Client Information" icon="bi bi-file-earmark-text-fill">
                <div class="form-group">
                    
                    <div class="row">
                        @if($role == 'Admin')
                            {{-- Admin sees dropdown to select client --}}
                            <div class="col-lg-6 mb-3">
                                <label for="client_id" class="form-label">Client Name</label>
                                <x-select name="client_id" placeholder="Select Client Name" required>
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
                            <div class="col-lg-6 mb-3">
                                <label for="client_name" class="form-label">Client Name</label>
                                <x-input name="client_name" type="text" value="{{ Auth::user()->name }}" required/>
                                <input type="hidden" name="client_id" value="{{ Auth::id() }}">
                            </div>
                        @endif
                        
                        <div class="col-lg-6 mb-3">
                            <label for="email" class="form-label">Billing email(s)</label>
                            <x-input name="email" type="email" placeholder="Enter billing email(s)" required/>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="primary_contact_info" class="form-label">Primary contact info</label>
                            <x-input name="primary_contact_info" type="text" placeholder="Enter primary contact info" required/>
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
                                'type' => 'text',
                            ],
                            [
                                'label' => 'DBA (Assumed Name)',
                                'name' => 'dba',
                                'type' => 'text',
                            ],
                            [
                                'label' => 'FEIN',
                                'name' => 'fein',
                                'type' => 'text',
                                'maxlength' => 9,
                                'pattern' => '[0-9]{9}',
                                'placeholder' => '9 digit numerical',
                            ],
                            [
                                'label' => 'Sales Tax ID',
                                'name' => 'sales_tax_id',
                                'type' => 'text',
                            ],
                        ];
                    @endphp
                    <div class="row">
                        @foreach ($client as $field)
                            <div class="col-lg-6 mb-3">
                                <label for="{{ $field['name'] }}" class="form-label">{{ $field['label'] }}</label>
                                <x-input 
                                    :maxlength="$field['maxlength'] ?? null" 
                                    :pattern="$field['pattern'] ?? null"
                                    name="{{ $field['name'] }}" 
                                    type="{{ $field['type'] }}" 
                                    required
                                    placeholder="Enter {{ strtolower($field['label']) }}" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-card>
        </div>

    <div class="my-2">
        <x-card title="Store Location" icon="bi bi-geo-alt-fill">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-3 mb-3">
                        <label for="street_number" class="form-label">Street Number</label>
                        <x-input name="street_number" type="text" placeholder="Enter street number" required/>
                    </div>
                    <div class="col-lg-9 mb-3">
                        <label for="street_name" class="form-label">Street Name</label>
                        <x-input name="street_name" type="text" placeholder="Enter street name" required/>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="city" class="form-label">City</label>
                        <x-input name="city" type="text" placeholder="Enter city" required/>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="county" class="form-label">County</label>
                        <x-input name="county" type="text" placeholder="Enter county" required/>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="state" class="form-label">State</label>
                        <x-select name="state" id="state" class="form-select" required>
                            <option value="">Select State</option>
                        </x-select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="zip_code" class="form-label">Zip Code</label>
                        <x-input name="zip_code" type="text" placeholder="Enter zip code" required/>
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
                        @php
                            $permit_types = \App\Models\PermitType::with('activeSubPermits')->where('is_active', 1)->get();
                        @endphp
                        <x-select name="permit_type" id="permit_type" placeholder="Select Permit Type" required>
                            @forelse ($permit_types as $type)
                                <option value="{{ $type->permit_type }}" 
                                    data-id="{{ $type->id }}"
                                    data-jurisdiction="{{ $type->jurisdiction_level }}"
                                    data-agency="{{ $type->agency_name }}">{{ $type->permit_type }}</option>
                            @empty
                                <option value="" disabled>No Permit Type Available</option>
                            @endforelse
                        </x-select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="permit_subtype" class="form-label">Permit Subtype <span class="text-muted">(Optional)</span></label>
                        <x-select name="permit_subtype" id="permit_subtype" placeholder="Select Permit Subtype">
                            <option value="">-- Select Permit Type First --</option>
                        </x-select>
                        <small class="text-muted">Sub-permits are based on the selected permit type</small>
                    </div>
                    
                    <!-- Auto-populated from Permit Type -->
                    <div class="col-lg-6 mb-3">
                        <label for="jurisdiction_level" class="form-label">Jurisdiction Level</label>
                        <x-input name="jurisdiction_level" id="jurisdiction_level" type="text" readonly placeholder="Auto-populated from Permit Type"/>
                        <small class="text-muted">Automatically set based on permit type</small>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="agency_name" class="form-label">Agency Name</label>
                        <x-input name="agency_name" id="agency_name" type="text" readonly placeholder="Auto-populated from Permit Type"/>
                        <small class="text-muted">Automatically set based on permit type</small>
                    </div>
                    
                    <div class="col-lg-6 mb-3">
                        <label class="form-label" for="permit_number">Permit Number</label>
                        <x-input type="text" name="permit_number" id="permit_number" readonly placeholder="Auto-generated"/>
                        <small class="text-muted">Automatically generated when license is created</small>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
    @if(Auth::user()->Role->name != "Client")
    <div class="my-2">
        <x-card title="Additional Information" icon="bi bi-plus-circle">
            <p class="text-muted mb-3">Add requirements or additional information for this license. You can optionally include the client's response directly.</p>
            
            <!-- Template for dynamic fields (hidden) -->
            <template id="requirement-template">
                <div class="content-card mb-3" style="border: 1px solid #dee2e6; border-radius: 8px;">
                    <div class="content-card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="card-title mb-0 requirement-title">Requirement #1</h6>
                            <button type="button" class="btn btn-danger-custom btn-sm remove-btn">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 mb-3">
                                <label class="form-label">Requirement Label <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-custom" name="requirements[0][label]" placeholder="e.g., Business License Copy" required>
                            </div>
                            <div class="col-lg-7 mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control form-control-custom" name="requirements[0][description]" placeholder="Additional details about this requirement">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Client Response</label>
                                <textarea class="form-control form-control-custom" name="requirements[0][value]" rows="3" placeholder="Enter client's response (optional)"></textarea>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Upload File (optional)</label>
                                <input type="file" class="form-control" name="requirement_files[0]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                                <small class="text-muted">Max file size: 10MB</small>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            
            <!-- Dynamic Fields Container -->
            <div id="dynamic-fields-container">
            </div>
            
            <div class="d-flex gap-2 mt-3">
                <x-button type="button" variant="primary" onclick="addField()" icon="bi bi-plus-lg">Add Another</x-button>
            </div>
        </x-card>
    </div>
    @endif

    <div class="my-3 d-flex justify-content-end gap-2">
        <x-button href="{{ route('admin.licenses.index') }}" variant="outline">Cancel</x-button>
        <x-button type="button" variant="gold" icon="bi bi-eye" onclick="showPreviewModal()">Preview & Proceed to Payment</x-button>
    </div>

    </form>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel"><i class="bi bi-file-earmark-text"></i> Review Your License Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Client Information -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-person"></i> Client Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6"><strong>Client Name:</strong> <span id="preview-client-name"></span></div>
                                <div class="col-md-6"><strong>Billing Email:</strong> <span id="preview-email"></span></div>
                                <div class="col-md-6"><strong>Primary Contact:</strong> <span id="preview-contact"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Business Entity -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-building"></i> Business Entity</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6"><strong>Legal Name:</strong> <span id="preview-legal-name"></span></div>
                                <div class="col-md-6"><strong>DBA:</strong> <span id="preview-dba"></span></div>
                                <div class="col-md-6"><strong>FEIN:</strong> <span id="preview-fein"></span></div>
                                <div class="col-md-6"><strong>Sales Tax ID:</strong> <span id="preview-sales-tax-id"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Store Location -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Store Location</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12"><strong>Address:</strong> <span id="preview-address"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Permit Details -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-file-earmark-text"></i> Permit / License Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6"><strong>Permit Type:</strong> <span id="preview-permit-type"></span></div>
                                <div class="col-md-6"><strong>Permit Subtype:</strong> <span id="preview-permit-subtype"></span></div>
                                <div class="col-md-6"><strong>Jurisdiction:</strong> <span id="preview-jurisdiction"></span></div>
                                <div class="col-md-6"><strong>Agency:</strong> <span id="preview-agency"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="bi bi-currency-dollar"></i> Payment Summary</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm mb-0">
                                <tbody id="preview-fees-body">
                                    <!-- Fees will be populated dynamically -->
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Total Amount Due</th>
                                        <th class="text-end" id="preview-total">$0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-pencil"></i> Edit Application
                    </button>
                    @if($role == 'Admin')
                        <button type="button" class="btn btn-success" onclick="submitWithOverride()">
                            <i class="bi bi-check-circle"></i> Mark as Paid (Override)
                        </button>
                    @endif
                    <button type="button" class="btn btn-gold" onclick="submitAndPay()">
                        <i class="bi bi-credit-card"></i> Proceed to Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
// Permit Type data (global scope for modal access)
@php
    $permitTypesJson = $permit_types->mapWithKeys(function($type) {
        return [$type->id => [
            'name' => $type->permit_type,
            'jurisdiction_level' => $type->jurisdiction_level,
            'agency_name' => $type->agency_name,
            'government_fee' => $type->government_fee ?? 0,
            'cls_service_fee' => $type->cls_service_fee ?? 0,
            'city_county_fee' => $type->city_county_fee ?? 0,
            'additional_fee' => $type->additional_fee ?? 0,
            'additional_fee_description' => $type->additional_fee_description,
            'subPermits' => $type->activeSubPermits->map(function($sub) {
                return ['id' => $sub->id, 'name' => $sub->name];
            })
        ]];
    });
@endphp
const permitTypesData = @json($permitTypesJson);

document.addEventListener('DOMContentLoaded', function() {
    // FEIN input - restrict to 9 digits only
    const feinInput = document.querySelector('input[name="fein"]');
    if (feinInput) {
        feinInput.addEventListener('input', function(e) {
            // Remove non-digits and limit to 9 characters
            this.value = this.value.replace(/\D/g, '').slice(0, 9);
        });
    }

    const permitTypeSelect = document.getElementById('permit_type');
    const permitSubtypeSelect = document.getElementById('permit_subtype');
    const jurisdictionLevelInput = document.getElementById('jurisdiction_level');
    const agencyNameInput = document.getElementById('agency_name');

    // Handle permit type change - auto-populate jurisdiction, agency, and sub-permits
    permitTypeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const permitTypeId = selectedOption.getAttribute('data-id');
        const jurisdiction = selectedOption.getAttribute('data-jurisdiction');
        const agency = selectedOption.getAttribute('data-agency');
        
        // Auto-populate jurisdiction level
        if (jurisdictionLevelInput) {
            jurisdictionLevelInput.value = jurisdiction ? jurisdiction.charAt(0).toUpperCase() + jurisdiction.slice(1) : '';
        }
        
        // Auto-populate agency name
        if (agencyNameInput) {
            agencyNameInput.value = agency || '';
        }
        
        // Clear sub-permits dropdown
        permitSubtypeSelect.innerHTML = '<option value="">-- No Sub-Permit --</option>';
        
        if (permitTypeId && permitTypesData[permitTypeId]) {
            const subPermits = permitTypesData[permitTypeId].subPermits;
            
            if (subPermits && subPermits.length > 0) {
                permitSubtypeSelect.innerHTML = '<option value="">-- Select Sub-Permit (Optional) --</option>';
                subPermits.forEach(function(subPermit) {
                    const option = document.createElement('option');
                    option.value = subPermit.name;
                    option.textContent = subPermit.name;
                    permitSubtypeSelect.appendChild(option);
                });
            } else {
                permitSubtypeSelect.innerHTML = '<option value="">-- No Sub-Permits Available --</option>';
            }
        }
    });

    // Load US States for Store Location
    const stateSelect = document.getElementById('state');
    const usStates = [
        { code: 'AL', name: 'Alabama' }, { code: 'AK', name: 'Alaska' }, { code: 'AZ', name: 'Arizona' },
        { code: 'AR', name: 'Arkansas' }, { code: 'CA', name: 'California' }, { code: 'CO', name: 'Colorado' },
        { code: 'CT', name: 'Connecticut' }, { code: 'DE', name: 'Delaware' }, { code: 'FL', name: 'Florida' },
        { code: 'GA', name: 'Georgia' }, { code: 'HI', name: 'Hawaii' }, { code: 'ID', name: 'Idaho' },
        { code: 'IL', name: 'Illinois' }, { code: 'IN', name: 'Indiana' }, { code: 'IA', name: 'Iowa' },
        { code: 'KS', name: 'Kansas' }, { code: 'KY', name: 'Kentucky' }, { code: 'LA', name: 'Louisiana' },
        { code: 'ME', name: 'Maine' }, { code: 'MD', name: 'Maryland' }, { code: 'MA', name: 'Massachusetts' },
        { code: 'MI', name: 'Michigan' }, { code: 'MN', name: 'Minnesota' }, { code: 'MS', name: 'Mississippi' },
        { code: 'MO', name: 'Missouri' }, { code: 'MT', name: 'Montana' }, { code: 'NE', name: 'Nebraska' },
        { code: 'NV', name: 'Nevada' }, { code: 'NH', name: 'New Hampshire' }, { code: 'NJ', name: 'New Jersey' },
        { code: 'NM', name: 'New Mexico' }, { code: 'NY', name: 'New York' }, { code: 'NC', name: 'North Carolina' },
        { code: 'ND', name: 'North Dakota' }, { code: 'OH', name: 'Ohio' }, { code: 'OK', name: 'Oklahoma' },
        { code: 'OR', name: 'Oregon' }, { code: 'PA', name: 'Pennsylvania' }, { code: 'RI', name: 'Rhode Island' },
        { code: 'SC', name: 'South Carolina' }, { code: 'SD', name: 'South Dakota' }, { code: 'TN', name: 'Tennessee' },
        { code: 'TX', name: 'Texas' }, { code: 'UT', name: 'Utah' }, { code: 'VT', name: 'Vermont' },
        { code: 'VA', name: 'Virginia' }, { code: 'WA', name: 'Washington' }, { code: 'WV', name: 'West Virginia' },
        { code: 'WI', name: 'Wisconsin' }, { code: 'WY', name: 'Wyoming' }, { code: 'DC', name: 'District of Columbia' }
    ];

    if (stateSelect) {
        let stateOptions = '<option value="">Select State</option>';
        usStates.forEach(state => {
            stateOptions += `<option value="${state.code}">${state.name}</option>`;
        });
        stateSelect.innerHTML = stateOptions;
    }
});

// Dynamic Additional Fields
let fieldCounter = 0;

function addField() {
    fieldCounter++;
    const container = document.getElementById('dynamic-fields-container');
    const template = document.getElementById('requirement-template');
    
    // Clone the template content
    const clone = template.content.cloneNode(true);
    
    // Get the card element (x-card renders to content-card class)
    const card = clone.querySelector('.content-card');
    if (!card) {
        console.error('Card element not found in template');
        return;
    }
    card.id = `dynamic-field-${fieldCounter}`;
    
    // Update the title
    const title = clone.querySelector('.requirement-title');
    if (title) {
        title.textContent = `Requirement #${fieldCounter}`;
    }
    
    // Update all input/textarea names with the correct counter
    clone.querySelectorAll('[name]').forEach(input => {
        input.name = input.name.replace('[0]', `[${fieldCounter}]`);
        // Also update the id if it exists
        if (input.id) {
            input.id = input.id.replace('[0]', `[${fieldCounter}]`);
        }
    });
    
    // Add remove button functionality
    const removeBtn = clone.querySelector('.remove-btn');
    if (removeBtn) {
        const currentCounter = fieldCounter;
        removeBtn.onclick = function() {
            removeField(currentCounter);
        };
    }
    
    container.appendChild(clone);
}

function removeField(id) {
    const field = document.getElementById(`dynamic-field-${id}`);
    if (field) {
        field.remove();
    }
}

// Preview Modal Functions
function getFieldValue(name) {
    const field = document.querySelector(`[name="${name}"]`);
    if (!field) return '';
    if (field.tagName === 'SELECT') {
        return field.options[field.selectedIndex]?.text || '';
    }
    return field.value || '';
}

function showPreviewModal() {
    const form = document.getElementById('license-form');
    
    // Basic validation
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Populate Client Information
    const clientNameField = document.querySelector('[name="client_name"]') || document.querySelector('[name="client_id"]');
    if (clientNameField) {
        if (clientNameField.tagName === 'SELECT') {
            document.getElementById('preview-client-name').textContent = clientNameField.options[clientNameField.selectedIndex]?.text || '';
        } else {
            document.getElementById('preview-client-name').textContent = clientNameField.value;
        }
    }
    document.getElementById('preview-email').textContent = getFieldValue('email');
    document.getElementById('preview-contact').textContent = getFieldValue('primary_contact_info');

    // Populate Business Entity
    document.getElementById('preview-legal-name').textContent = getFieldValue('legal_name');
    document.getElementById('preview-dba').textContent = getFieldValue('dba') || 'N/A';
    document.getElementById('preview-fein').textContent = getFieldValue('fein');
    document.getElementById('preview-sales-tax-id').textContent = getFieldValue('sales_tax_id') || 'N/A';

    // Populate Store Location
    const streetNum = getFieldValue('street_number');
    const streetName = getFieldValue('street_name');
    const city = getFieldValue('city');
    const county = getFieldValue('county');
    const stateSelect = document.getElementById('state');
    const state = stateSelect.options[stateSelect.selectedIndex]?.text || '';
    const zipCode = getFieldValue('zip_code');
    document.getElementById('preview-address').textContent = `${streetNum} ${streetName}, ${city}, ${county}, ${state} ${zipCode}`;

    // Populate Permit Details
    document.getElementById('preview-permit-type').textContent = getFieldValue('permit_type');
    document.getElementById('preview-permit-subtype').textContent = getFieldValue('permit_subtype') || 'N/A';
    document.getElementById('preview-jurisdiction').textContent = getFieldValue('jurisdiction_level') || 'N/A';
    document.getElementById('preview-agency').textContent = getFieldValue('agency_name') || 'N/A';

    // Populate Payment Summary from Permit Type fees
    const permitTypeSelect = document.getElementById('permit_type');
    const permitTypeId = permitTypeSelect.options[permitTypeSelect.selectedIndex]?.getAttribute('data-id');
    
    let feesHtml = '';
    let total = 0;

    if (permitTypeId && permitTypesData[permitTypeId]) {
        const fees = permitTypesData[permitTypeId];
        
        if (parseFloat(fees.government_fee) > 0) {
            feesHtml += `<tr><td>Government Fee</td><td class="text-end">$${parseFloat(fees.government_fee).toFixed(2)}</td></tr>`;
            total += parseFloat(fees.government_fee);
        }
        if (parseFloat(fees.cls_service_fee) > 0) {
            feesHtml += `<tr><td>CLS-360 Service Fee</td><td class="text-end">$${parseFloat(fees.cls_service_fee).toFixed(2)}</td></tr>`;
            total += parseFloat(fees.cls_service_fee);
        }
        if (parseFloat(fees.city_county_fee) > 0) {
            feesHtml += `<tr><td>City/County Fee</td><td class="text-end">$${parseFloat(fees.city_county_fee).toFixed(2)}</td></tr>`;
            total += parseFloat(fees.city_county_fee);
        }
        if (parseFloat(fees.additional_fee) > 0) {
            const desc = fees.additional_fee_description ? ` (${fees.additional_fee_description})` : '';
            feesHtml += `<tr><td>Additional Fee${desc}</td><td class="text-end">$${parseFloat(fees.additional_fee).toFixed(2)}</td></tr>`;
            total += parseFloat(fees.additional_fee);
        }
    }

    if (feesHtml === '') {
        feesHtml = '<tr><td colspan="2" class="text-center text-muted">No fees configured for this permit type</td></tr>';
    }

    document.getElementById('preview-fees-body').innerHTML = feesHtml;
    document.getElementById('preview-total').textContent = `$${total.toFixed(2)}`;

    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}

function submitAndPay() {
    document.getElementById('payment-action').value = 'stripe';
    document.getElementById('license-form').submit();
}

function submitWithOverride() {
    document.getElementById('payment-action').value = 'override';
    document.getElementById('license-form').submit();
}
</script>
@endpush
