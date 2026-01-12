@extends('layouts.index')

@section('title', 'License Payment')

@section('content')
    @php
        $role = Auth::user()->Role->name;
        $isAdminAgent = in_array($role, ['Admin', 'Agent']);
        $isClient = $role === 'Client';
    @endphp

    <x-page-header title="License Payment" subtitle="Transaction ID: {{ $license->transaction_id }}">
        <x-button href="{{ route('admin.licenses.show', $license) }}" variant="secondary" icon="bi bi-arrow-left">Back to License</x-button>
        @if($isAdminAgent && (!$payment || $payment->isCancelled()))
            <x-button href="{{ route('admin.licenses.payments.create', $license) }}" variant="gold" icon="bi bi-plus-lg">Create Payment</x-button>
        @endif
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

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($payment)
        <div class="row">
            <div class="col-lg-8">
                <!-- Payment Status Card -->
                <x-card class="mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Payment Status</h5>
                            @switch($payment->status)
                                @case('draft')
                                    <x-badge type="secondary">Draft</x-badge>
                                    @break
                                @case('open')
                                    <x-badge type="warning">Open - Awaiting Payment</x-badge>
                                    @break
                                @case('paid')
                                    <x-badge type="success">Paid</x-badge>
                                    @break
                                @case('cancelled')
                                    <x-badge type="danger">Cancelled</x-badge>
                                    @break
                                @case('overridden')
                                    <x-badge type="info">Overridden</x-badge>
                                    @break
                            @endswitch
                        </div>
                        <div>
                            <small class="text-muted">Invoice: {{ $payment->invoice_number }}</small>
                        </div>
                    </div>
                </x-card>

                <!-- Payment Items Card -->
                <x-card title="Payment Details" icon="bi bi-receipt" :padding="false" class="mb-4">
                    <x-table>
                        <x-slot:head>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                                @if($isAdminAgent && $payment->isOpen())
                                    <th class="text-end">Actions</th>
                                @endif
                            </tr>
                        </x-slot:head>
                        @foreach($payment->items as $item)
                            <tr>
                                <td><strong>{{ $item->label }}</strong></td>
                                <td>{{ $item->description ?? '-' }}</td>
                                <td class="text-end">${{ number_format($item->amount, 2) }}</td>
                                @if($isAdminAgent && $payment->isOpen())
                                    <td class="text-end">
                                        <form action="{{ route('admin.licenses.payments.remove-item', [$license, $payment, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        <tr class="table-light">
                            <td colspan="{{ $isAdminAgent && $payment->isOpen() ? 2 : 2 }}"><strong>Total</strong></td>
                            <td class="text-end"><strong>${{ number_format($payment->total_amount, 2) }}</strong></td>
                            @if($isAdminAgent && $payment->isOpen())
                                <td></td>
                            @endif
                        </tr>
                    </x-table>
                </x-card>

                <!-- Add Item Form (Admin/Agent - if payment is open) -->
                @if($isAdminAgent && $payment->isOpen())
                <x-card title="Add Payment Item" icon="bi bi-plus-circle" class="mb-4">
                    <form action="{{ route('admin.licenses.payments.add-item', [$license, $payment]) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Label <span class="text-danger">*</span></label>
                                <x-input name="label" type="text" placeholder="e.g., Processing Fee" required />
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Description</label>
                                <x-input name="description" type="text" placeholder="Optional description" />
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label class="form-label">Amount ($) <span class="text-danger">*</span></label>
                                <x-input name="amount" type="number" step="0.01" min="0.01" placeholder="0.00" required />
                            </div>
                            <div class="col-lg-2 mb-3 d-flex align-items-end">
                                <x-button type="submit" variant="gold" icon="bi bi-plus">Add</x-button>
                            </div>
                        </div>
                    </form>
                </x-card>
                @endif

                <!-- Payment Notes -->
                @if($payment->notes)
                <x-card title="Notes" icon="bi bi-sticky" class="mb-4">
                    <p class="mb-0">{{ $payment->notes }}</p>
                </x-card>
                @endif

                <!-- Payment History/Details -->
                @if($payment->isPaid() || $payment->isOverridden())
                <x-card title="Payment Information" icon="bi bi-info-circle" class="mb-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Payment Method</label>
                            <p class="mb-0">{{ ucfirst($payment->payment_method) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Paid At</label>
                            <p class="mb-0">{{ $payment->paid_at ? $payment->paid_at->format('M d, Y h:i A') : 'N/A' }}</p>
                        </div>
                        @if($payment->stripe_payment_intent_id)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Stripe Payment ID</label>
                            <p class="mb-0"><code>{{ $payment->stripe_payment_intent_id }}</code></p>
                        </div>
                        @endif
                        @if($payment->isOverridden())
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Override Reason</label>
                            <p class="mb-0">{{ $payment->override_reason }}</p>
                            <small class="text-muted">Overridden by: {{ $payment->overrider->name ?? 'N/A' }}</small>
                        </div>
                        @endif
                    </div>
                </x-card>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Payment Actions -->
                @if($payment->isOpen())
                <x-card title="Payment Actions" icon="bi bi-credit-card" class="mb-4">
                    <!-- Client: Pay Online -->
                    <div class="d-grid gap-3">
                        <div>
                            <h6><i class="bi bi-credit-card"></i> Pay Online</h6>
                            <p class="text-muted small mb-2">Secure payment via credit/debit card</p>
                            <form action="{{ route('admin.licenses.payments.checkout', [$license, $payment]) }}" method="POST">
                                @csrf
                                <x-button type="submit" variant="gold" class="w-100" icon="bi bi-credit-card">
                                    Pay Now - ${{ number_format($payment->total_amount, 2) }}
                                </x-button>
                            </form>
                        </div>

                        <hr>

                        <!-- Admin/Agent: Offline Payment - POS Style -->
                        @if($isAdminAgent)
                        <div>
                            <h6><i class="bi bi-cash-stack"></i> Record Offline Payment (POS)</h6>
                            <p class="text-muted small mb-2">Client paid over the counter/in person</p>
                            
                            <!-- POS Style Display -->
                            <div class="bg-dark text-white p-3 rounded mb-3" id="pos-display">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Total Due:</span>
                                    <span class="fs-4 fw-bold text-warning">${{ number_format($payment->total_amount, 2) }}</span>
                                </div>
                                <hr class="border-secondary my-2">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Amount Received:</span>
                                    <span class="fs-5 text-success" id="display-received">$0.00</span>
                                </div>
                                <hr class="border-secondary my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Change:</span>
                                    <span class="fs-4 fw-bold" id="display-change">$0.00</span>
                                </div>
                            </div>

                            <form action="{{ route('admin.licenses.payments.pay-offline', [$license, $payment]) }}" method="POST" id="pos-form">
                                @csrf
                                <input type="hidden" name="total_amount" value="{{ $payment->total_amount }}">
                                
                                <div class="mb-3">
                                    <label class="form-label">Amount Received ($)</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               name="amount_received" 
                                               id="amount-received" 
                                               class="form-control form-control-lg text-end" 
                                               step="0.01" 
                                               min="{{ $payment->total_amount }}"
                                               placeholder="0.00"
                                               required
                                               oninput="calculateChange({{ $payment->total_amount }})"
                                               onchange="calculateChange({{ $payment->total_amount }})">
                                    </div>
                                </div>

                                <!-- Quick amount buttons -->
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount({{ $payment->total_amount }})">Exact</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount({{ ceil($payment->total_amount / 10) * 10 }})">$@php echo ceil($payment->total_amount / 10) * 10; @endphp</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount({{ ceil($payment->total_amount / 50) * 50 }})">$@php echo ceil($payment->total_amount / 50) * 50; @endphp</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount({{ ceil($payment->total_amount / 100) * 100 }})">$@php echo ceil($payment->total_amount / 100) * 100; @endphp</button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Receipt/Notes (optional)</label>
                                    <x-input name="notes" type="text" placeholder="Receipt number or notes" />
                                </div>

                                <x-button type="submit" variant="success" class="w-100" icon="bi bi-cash-coin" id="pos-submit-btn" disabled>
                                    <i class="bi bi-check-circle me-1"></i> Complete Payment
                                </x-button>
                            </form>
                        </div>

                        <hr>

                        <div>
                            <h6><i class="bi bi-shield-check"></i> Override Payment</h6>
                            <p class="text-muted small mb-2">Waive payment requirement</p>
                            <x-button type="button" variant="outline-warning" class="w-100" data-bs-toggle="modal" data-bs-target="#overrideModal">
                                Override Payment
                            </x-button>
                        </div>

                        <hr>

                        <div>
                            <h6><i class="bi bi-x-circle"></i> Cancel Payment</h6>
                            <form action="{{ route('admin.licenses.payments.destroy', [$license, $payment]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="outline-danger" class="w-100" onclick="return confirm('Cancel this payment?')">
                                    Cancel Payment
                                </x-button>
                            </form>
                        </div>
                        @else
                        <div>
                            <h6><i class="bi bi-cash"></i> Pay Offline</h6>
                            <p class="text-muted small">Contact our office to pay in person</p>
                        </div>
                        @endif
                    </div>
                </x-card>
                @endif

                <!-- License Info -->
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
                        <p class="mb-0">{{ $license->expiration_date ? $license->expiration_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Override Modal -->
        @if($isAdminAgent && $payment->isOpen())
        <div class="modal fade" id="overrideModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.licenses.payments.override', [$license, $payment]) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Override Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> This will waive the payment requirement and mark the license as paid.
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Override Reason <span class="text-danger">*</span></label>
                                <x-textarea name="override_reason" rows="3" placeholder="Explain why payment is being waived..." required></x-textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <x-button type="submit" variant="warning">Override Payment</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Payment History Section -->
        @if($paymentHistory && $paymentHistory->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center py-3">
                                <div class="rounded-circle bg-secondary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                                    <i class="bi bi-receipt fs-4 text-secondary"></i>
                                </div>
                                <h3 class="mb-0 fw-bold">{{ $paymentHistory->count() }}</h3>
                                <small class="text-muted">Total Payments</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                            <div class="card-body text-center py-3">
                                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                                    <i class="bi bi-check-circle fs-4 text-success"></i>
                                </div>
                                <h3 class="mb-0 fw-bold text-success">${{ number_format($paymentHistory->where('status', 'paid')->sum('total_amount'), 2) }}</h3>
                                <small class="text-muted">Paid</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
                            <div class="card-body text-center py-3">
                                <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                                    <i class="bi bi-shield-check fs-4 text-info"></i>
                                </div>
                                <h3 class="mb-0 fw-bold text-info">${{ number_format($paymentHistory->where('status', 'overridden')->sum('total_amount'), 2) }}</h3>
                                <small class="text-muted">Overridden</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                            <div class="card-body text-center py-3">
                                <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                                    <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                                </div>
                                <h3 class="mb-0 fw-bold text-warning">${{ number_format($paymentHistory->whereIn('status', ['draft', 'open'])->sum('total_amount'), 2) }}</h3>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                    </div>
                </div>

                <x-card title="Payment History" icon="bi bi-clock-history" :padding="false">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Invoice</th>
                                    <th class="py-3">Date</th>
                                    <th class="py-3">Items</th>
                                    <th class="py-3 text-end">Amount</th>
                                    <th class="py-3 text-center">Method</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="py-3">Processed By</th>
                                    <th class="py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentHistory as $historyPayment)
                                <tr class="{{ $payment && $historyPayment->id === $payment->id ? 'bg-primary bg-opacity-10' : '' }}">
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded bg-light p-2 me-3">
                                                <i class="bi bi-file-text fs-5 text-primary"></i>
                                            </div>
                                            <div>
                                                <span class="fw-semibold d-block">{{ $historyPayment->invoice_number }}</span>
                                                @if($payment && $historyPayment->id === $payment->id)
                                                    <span class="badge bg-primary bg-opacity-75 rounded-pill mt-1">
                                                        <i class="bi bi-arrow-right-circle me-1"></i>Current
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="fw-medium">{{ $historyPayment->created_at->format('M d, Y') }}</span>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $historyPayment->created_at->format('h:i A') }}
                                        </small>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex flex-column gap-1">
                                            @foreach($historyPayment->items->take(2) as $item)
                                                <span class="badge bg-light text-dark border">
                                                    <i class="bi bi-dot"></i>{{ Str::limit($item->label, 20) }}
                                                </span>
                                            @endforeach
                                            @if($historyPayment->items->count() > 2)
                                                <span class="text-muted small">
                                                    <i class="bi bi-plus-circle me-1"></i>{{ $historyPayment->items->count() - 2 }} more
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 text-end">
                                        <span class="fs-5 fw-bold text-dark">${{ number_format($historyPayment->total_amount, 2) }}</span>
                                    </td>
                                    <td class="py-3 text-center">
                                        @if($historyPayment->payment_method)
                                            @if($historyPayment->payment_method === 'online')
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                    <i class="bi bi-credit-card me-1"></i>Online
                                                </span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                                    <i class="bi bi-cash-stack me-1"></i>Offline
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        @switch($historyPayment->status)
                                            @case('draft')
                                                <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                                    <i class="bi bi-pencil me-1"></i>Draft
                                                </span>
                                                @break
                                            @case('open')
                                                <span class="badge bg-warning px-3 py-2 rounded-pill">
                                                    <i class="bi bi-hourglass-split me-1"></i>Open
                                                </span>
                                                @break
                                            @case('paid')
                                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                                    <i class="bi bi-check-circle me-1"></i>Paid
                                                </span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                    <i class="bi bi-x-circle me-1"></i>Cancelled
                                                </span>
                                                @break
                                            @case('overridden')
                                                <span class="badge bg-info px-3 py-2 rounded-pill">
                                                    <i class="bi bi-shield-check me-1"></i>Overridden
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="py-3">
                                        @if($historyPayment->isPaid() || $historyPayment->isOverridden())
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-person text-secondary"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-medium">{{ $historyPayment->payer->name ?? $historyPayment->creator->name ?? 'N/A' }}</span>
                                                    @if($historyPayment->paid_at)
                                                        <br><small class="text-muted">{{ $historyPayment->paid_at->format('M d, Y') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        <button type="button" 
                                                class="btn btn-sm btn-light border rounded-circle p-2" 
                                                style="width: 36px; height: 36px;"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#paymentDetailModal{{ $historyPayment->id }}"
                                                title="View Details">
                                            <i class="bi bi-eye text-primary"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Payment Detail Modals -->
        @foreach($paymentHistory as $historyPayment)
        <div class="modal fade" id="paymentDetailModal{{ $historyPayment->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-receipt me-2"></i>Payment Details - {{ $historyPayment->invoice_number }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Payment Status -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                @switch($historyPayment->status)
                                    @case('paid')
                                        <x-badge type="success">Paid</x-badge>
                                        @break
                                    @case('overridden')
                                        <x-badge type="info">Overridden</x-badge>
                                        @break
                                    @case('open')
                                        <x-badge type="warning">Open</x-badge>
                                        @break
                                    @case('cancelled')
                                        <x-badge type="danger">Cancelled</x-badge>
                                        @break
                                    @default
                                        <x-badge type="secondary">{{ ucfirst($historyPayment->status) }}</x-badge>
                                @endswitch
                            </div>
                            <small class="text-muted">Created: {{ $historyPayment->created_at->format('M d, Y h:i A') }}</small>
                        </div>

                        <!-- Items Table -->
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historyPayment->items as $item)
                                <tr>
                                    <td>{{ $item->label }}</td>
                                    <td>{{ $item->description ?? '-' }}</td>
                                    <td class="text-end">${{ number_format($item->amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th class="text-end">${{ number_format($historyPayment->total_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Payment Info -->
                        @if($historyPayment->isPaid() || $historyPayment->isOverridden())
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <strong>Payment Method:</strong><br>
                                {{ ucfirst($historyPayment->payment_method ?? 'N/A') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Paid At:</strong><br>
                                {{ $historyPayment->paid_at ? $historyPayment->paid_at->format('M d, Y h:i A') : 'N/A' }}
                            </div>
                        </div>
                        @endif

                        @if($historyPayment->stripe_payment_intent_id)
                        <div class="mt-3">
                            <strong>Stripe Payment ID:</strong><br>
                            <code>{{ $historyPayment->stripe_payment_intent_id }}</code>
                        </div>
                        @endif

                        @if($historyPayment->isOverridden() && $historyPayment->override_reason)
                        <div class="alert alert-info mt-3">
                            <strong>Override Reason:</strong><br>
                            {{ $historyPayment->override_reason }}
                        </div>
                        @endif

                        @if($historyPayment->notes)
                        <div class="mt-3">
                            <strong>Notes:</strong><br>
                            <pre class="bg-light p-2 rounded" style="white-space: pre-wrap;">{{ $historyPayment->notes }}</pre>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    @else
        <div class="row">
            <div class="col-12">
                <x-card>
                    <div class="text-center py-5">
                        <i class="bi bi-credit-card fs-1 text-muted d-block mb-3"></i>
                        <h5>No Active Payment</h5>
                        <p class="text-muted">There is no active payment for this license.</p>
                        @if($isAdminAgent && $license->canCreatePayment())
                            <x-button href="{{ route('admin.licenses.payments.create', $license) }}" variant="gold" icon="bi bi-plus-lg">Create Payment</x-button>
                        @elseif($isAdminAgent)
                            <p class="text-muted small">Payment creation is available when renewal status is open.</p>
                        @endif
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Payment History (when no active payment) -->
        @if($paymentHistory && $paymentHistory->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <x-card title="Payment History" icon="bi bi-clock-history" :padding="false">
                    <x-table>
                        <x-slot:head>
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th class="text-end">Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Processed By</th>
                                <th class="text-center">Details</th>
                            </tr>
                        </x-slot:head>
                        @foreach($paymentHistory as $historyPayment)
                            <tr>
                                <td><strong>{{ $historyPayment->invoice_number }}</strong></td>
                                <td>
                                    {{ $historyPayment->created_at->format('M d, Y') }}
                                    <br><small class="text-muted">{{ $historyPayment->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    @foreach($historyPayment->items->take(2) as $item)
                                        <small>• {{ $item->label }}</small><br>
                                    @endforeach
                                    @if($historyPayment->items->count() > 2)
                                        <small class="text-muted">+{{ $historyPayment->items->count() - 2 }} more</small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <strong>${{ number_format($historyPayment->total_amount, 2) }}</strong>
                                </td>
                                <td>
                                    @if($historyPayment->payment_method)
                                        @if($historyPayment->payment_method === 'online')
                                            <i class="bi bi-credit-card text-primary"></i> Online
                                        @else
                                            <i class="bi bi-cash-stack text-success"></i> Offline
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($historyPayment->status)
                                        @case('paid')
                                            <x-badge type="success">Paid</x-badge>
                                            @break
                                        @case('cancelled')
                                            <x-badge type="danger">Cancelled</x-badge>
                                            @break
                                        @case('overridden')
                                            <x-badge type="info">Overridden</x-badge>
                                            @break
                                        @default
                                            <x-badge type="secondary">{{ ucfirst($historyPayment->status) }}</x-badge>
                                    @endswitch
                                </td>
                                <td>
                                    @if($historyPayment->isPaid() || $historyPayment->isOverridden())
                                        {{ $historyPayment->payer->name ?? $historyPayment->creator->name ?? 'N/A' }}
                                        @if($historyPayment->paid_at)
                                            <br><small class="text-muted">{{ $historyPayment->paid_at->format('M d, Y') }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#paymentDetailModalNoActive{{ $historyPayment->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </x-table>
                    
                    <!-- Summary Footer -->
                    <div class="card-footer bg-light">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <strong class="text-muted">Total Payments</strong>
                                <h5 class="mb-0">{{ $paymentHistory->count() }}</h5>
                            </div>
                            <div class="col-md-4">
                                <strong class="text-success">Total Paid</strong>
                                <h5 class="mb-0 text-success">${{ number_format($paymentHistory->where('status', 'paid')->sum('total_amount'), 2) }}</h5>
                            </div>
                            <div class="col-md-4">
                                <strong class="text-info">Total Overridden</strong>
                                <h5 class="mb-0 text-info">${{ number_format($paymentHistory->where('status', 'overridden')->sum('total_amount'), 2) }}</h5>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Payment Detail Modals (no active payment) -->
        @foreach($paymentHistory as $historyPayment)
        <div class="modal fade" id="paymentDetailModalNoActive{{ $historyPayment->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-receipt me-2"></i>Payment Details - {{ $historyPayment->invoice_number }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                @switch($historyPayment->status)
                                    @case('paid')
                                        <x-badge type="success">Paid</x-badge>
                                        @break
                                    @case('overridden')
                                        <x-badge type="info">Overridden</x-badge>
                                        @break
                                    @default
                                        <x-badge type="secondary">{{ ucfirst($historyPayment->status) }}</x-badge>
                                @endswitch
                            </div>
                            <small class="text-muted">Created: {{ $historyPayment->created_at->format('M d, Y h:i A') }}</small>
                        </div>

                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historyPayment->items as $item)
                                <tr>
                                    <td>{{ $item->label }}</td>
                                    <td>{{ $item->description ?? '-' }}</td>
                                    <td class="text-end">${{ number_format($item->amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th class="text-end">${{ number_format($historyPayment->total_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>

                        @if($historyPayment->isPaid() || $historyPayment->isOverridden())
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <strong>Payment Method:</strong><br>
                                {{ ucfirst($historyPayment->payment_method ?? 'N/A') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Paid At:</strong><br>
                                {{ $historyPayment->paid_at ? $historyPayment->paid_at->format('M d, Y h:i A') : 'N/A' }}
                            </div>
                        </div>
                        @endif

                        @if($historyPayment->notes)
                        <div class="mt-3">
                            <strong>Notes:</strong><br>
                            <pre class="bg-light p-2 rounded" style="white-space: pre-wrap;">{{ $historyPayment->notes }}</pre>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    @endif
@endsection

@push('scripts')
<script>
function calculateChange(totalDue) {
    const amountReceived = parseFloat(document.getElementById('amount-received').value) || 0;
    const change = amountReceived - totalDue;
    
    // Update display
    document.getElementById('display-received').textContent = '$' + amountReceived.toFixed(2);
    
    const changeDisplay = document.getElementById('display-change');
    const submitBtn = document.getElementById('pos-submit-btn');
    
    if (amountReceived >= totalDue) {
        changeDisplay.textContent = '$' + change.toFixed(2);
        changeDisplay.classList.remove('text-danger');
        changeDisplay.classList.add('text-success');
        submitBtn.disabled = false;
    } else {
        const shortage = totalDue - amountReceived;
        changeDisplay.textContent = '-$' + shortage.toFixed(2);
        changeDisplay.classList.remove('text-success');
        changeDisplay.classList.add('text-danger');
        submitBtn.disabled = true;
    }
}

function setAmount(amount) {
    const input = document.getElementById('amount-received');
    input.value = amount.toFixed(2);
    // Trigger the calculation
    const totalDue = parseFloat(document.querySelector('input[name="total_amount"]').value);
    calculateChange(totalDue);
}

// Form submission confirmation with change info
document.getElementById('pos-form')?.addEventListener('submit', function(e) {
    const totalDue = parseFloat(document.querySelector('input[name="total_amount"]').value);
    const amountReceived = parseFloat(document.getElementById('amount-received').value);
    const change = amountReceived - totalDue;
    
    const confirmMsg = `Confirm Payment:\n\nTotal Due: $${totalDue.toFixed(2)}\nAmount Received: $${amountReceived.toFixed(2)}\nChange to Give: $${change.toFixed(2)}\n\nProceed with payment?`;
    
    if (!confirm(confirmMsg)) {
        e.preventDefault();
    }
});
</script>
@endpush
