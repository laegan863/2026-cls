@extends('layouts.index')

@section('title', 'Create Payment')

@section('content')
    <x-page-header title="Create Payment" subtitle="Transaction ID: {{ $license->transaction_id }}">
        <x-button href="{{ route('admin.licenses.show', $license) }}" variant="primary" icon="bi bi-arrow-left">Back to License</x-button>
    </x-page-header>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <x-card title="Payment Items" icon="bi bi-receipt">
                <form action="{{ route('admin.licenses.payments.store', $license) }}" method="POST" id="paymentForm">
                    @csrf
                    
                    <div id="items-container">
                        <div class="row mb-3 item-row border-bottom pb-3" data-index="0">
                            <div class="col-lg-4">
                                <label class="form-label">Label <span class="text-danger">*</span></label>
                                <x-input name="items[0][label]" type="text" placeholder="e.g., License Fee" required />
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Description</label>
                                <x-input name="items[0][description]" type="text" placeholder="Additional details" />
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label">Amount ($) <span class="text-danger">*</span></label>
                                <x-input name="items[0][amount]" type="number" step="0.01" min="0.01" placeholder="0.00" required onchange="calculateTotal()" onkeyup="calculateTotal()" />
                            </div>
                            <div class="col-lg-2 d-flex align-items-end">
                                <x-button type="button" variant="primary" onclick="removeItem(this)" icon="bi bi-trash"></x-button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <x-button type="button" variant="primary" onclick="addItem()" icon="bi bi-plus-lg">Add Item</x-button>
                        
                        <div class="text-end">
                            <h4 class="mb-0">Total: $<span id="totalAmount">0.00</span></h4>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <label class="form-label">Notes (Optional)</label>
                        <x-textarea name="notes" rows="2" placeholder="Additional notes for this payment..."></x-textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <x-button href="{{ route('admin.licenses.show', $license) }}" variant="primary">Cancel</x-button>
                        <x-button type="submit" variant="gold" icon="bi bi-send">Create & Send to Client</x-button>
                    </div>
                </form>
            </x-card>
        </div>

        <div class="col-lg-4">
            <x-card title="License Details" icon="bi bi-info-circle">
                <div class="mb-3">
                    <label class="form-label fw-bold">Client</label>
                    <p class="mb-0">{{ $license->client->name ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <p class="mb-0">{{ $license->email ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Permit Type</label>
                    <p class="mb-0">{{ $license->permit_type ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Expiration Date</label>
                    <p class="mb-0">
                        {{ $license->expiration_date ? $license->expiration_date->format('M d, Y') : 'N/A' }}
                        @if($license->isWithinRenewalWindow())
                            <x-badge type="warning">Within Renewal Window</x-badge>
                        @endif
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Workflow Status</label>
                    <p class="mb-0"><x-badge type="{{ $license->workflow_status_badge }}">{{ $license->workflow_status_label }}</x-badge></p>
                </div>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
<script>
let itemIndex = 0;

function addItem() {
    itemIndex++;
    const container = document.getElementById('items-container');
    
    const row = document.createElement('div');
    row.className = 'row mb-3 item-row border-bottom pb-3';
    row.setAttribute('data-index', itemIndex);
    
    row.innerHTML = `
        <div class="col-lg-4">
            <label class="form-label">Label <span class="text-danger">*</span></label>
            <input type="text" name="items[${itemIndex}][label]" class="form-control" placeholder="e.g., License Fee" required />
        </div>
        <div class="col-lg-4">
            <label class="form-label">Description</label>
            <input type="text" name="items[${itemIndex}][description]" class="form-control" placeholder="Additional details" />
        </div>
        <div class="col-lg-2">
            <label class="form-label">Amount ($) <span class="text-danger">*</span></label>
            <input type="number" name="items[${itemIndex}][amount]" class="form-control" step="0.01" min="0.01" placeholder="0.00" required onchange="calculateTotal()" onkeyup="calculateTotal()" />
        </div>
        <div class="col-lg-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger-custom" onclick="removeItem(this)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(row);
}

function removeItem(button) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        button.closest('.item-row').remove();
        calculateTotal();
    } else {
        alert('At least one payment item is required.');
    }
}

function calculateTotal() {
    let total = 0;
    const amountInputs = document.querySelectorAll('input[name$="[amount]"]');
    amountInputs.forEach(input => {
        const value = parseFloat(input.value) || 0;
        total += value;
    });
    document.getElementById('totalAmount').textContent = total.toFixed(2);
}

// Calculate on page load
document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
@endpush
