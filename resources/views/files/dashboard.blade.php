@extends('layouts.index')

@section('title', 'Dashboard')

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 200px;
    }
    .stat-card {
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .stats-card.clickable {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .stats-card.clickable:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }
</style>
@endpush

@section('content')
    <!-- Page Header -->
    <x-page-header title="Dashboard" subtitle="{{ Auth::user()->hasPermission('view-overdue-active-licenses-and-renewal-open') ? 'Welcome back! Here\'s your renewal queue overview.' : 'Welcome back! Here\'s your store and license overview.' }}">
        @if(Auth::user()->hasPermission('view-overdue-active-licenses-and-renewal-open'))
        <form action="{{ route('admin.licenses.bulk-refresh') }}" method="POST" class="d-inline">
            @csrf
            <x-button type="submit" variant="outline" icon="bi bi-arrow-clockwise">Refresh All Status</x-button>
        </form>
        @endif
    </x-page-header>

    @if($isClient ?? false)
    <!-- Client Dashboard Stats -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Items Needing Attention" 
                value="{{ $clientStats['items_needing_attention'] ?? 0 }}" 
                icon="bi bi-bell-fill"
                :change="($clientStats['items_needing_attention'] ?? 0) > 0 ? 'Action required' : 'All clear'"
                :trend="($clientStats['items_needing_attention'] ?? 0) > 0 ? 'down' : 'up'"
                variant="gold"
                href="{{ route('admin.dashboard.details', 'items-needing-attention') }}"
            />
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Overdue" 
                value="{{ $dueDateStats['overdue'] ?? 0 }}" 
                icon="bi bi-exclamation-triangle"
                :change="($dueDateStats['overdue'] ?? 0) > 0 ? 'Action required' : 'None overdue'"
                :trend="($dueDateStats['overdue'] ?? 0) > 0 ? 'down' : 'up'"
                href="{{ route('admin.dashboard.details', 'overdue') }}"
            />
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Active Licenses" 
                value="{{ $dueDateStats['active'] ?? 0 }}" 
                icon="bi bi-check-circle"
                change="In good standing"
                trend="up"
                href="{{ route('admin.dashboard.details', 'active') }}"
            />
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Renewal Open" 
                value="{{ $renewalStatusStats['open'] ?? 0 }}" 
                icon="bi bi-arrow-repeat"
                change="In renewal window"
                trend="neutral"
                href="{{ route('admin.dashboard.details', 'renewal-open') }}"
            />
        </div>
    </div>

    <!-- Client Stats Row 2 -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="My Stores" 
                value="{{ $clientStats['total_stores'] ?? 0 }}" 
                icon="bi bi-shop"
                change="Registered stores"
                trend="neutral"
                variant="primary"
                href="{{ route('admin.dashboard.details', 'my-stores') }}"
            />
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="My Licenses" 
                value="{{ $clientStats['total_licenses'] ?? 0 }}" 
                icon="bi bi-file-earmark-text"
                change="Total licenses"
                trend="neutral"
                href="{{ route('admin.dashboard.details', 'my-licenses') }}"
            />
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Due This Week" 
                value="{{ $dueDateStats['due_this_week'] ?? 0 }}" 
                icon="bi bi-calendar-week"
                change="Expiring soon"
                :trend="($dueDateStats['due_this_week'] ?? 0) > 0 ? 'down' : 'neutral'"
                href="{{ route('admin.dashboard.details', 'due-this-week') }}"
            />
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Due This Month" 
                value="{{ $dueDateStats['due_this_month'] ?? 0 }}" 
                icon="bi bi-calendar-event"
                change="Expiring within 30 days"
                :trend="($dueDateStats['due_this_month'] ?? 0) > 0 ? 'down' : 'neutral'"
                href="{{ route('admin.dashboard.details', 'due-this-month') }}"
            />
        </div>
    </div>
    @endif

    @if(Auth::user()->hasPermission('view-overdue-active-licenses-and-renewal-open'))
    <!-- Stats Overview Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Overdue" 
                value="{{ $dueDateStats['overdue'] ?? 0 }}" 
                icon="bi bi-exclamation-triangle"
                :change="($dueDateStats['overdue'] ?? 0) > 0 ? 'Action required' : 'None overdue'"
                :trend="($dueDateStats['overdue'] ?? 0) > 0 ? 'down' : 'up'"
                href="{{ route('admin.dashboard.details', 'overdue') }}"
            />
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Active Licenses" 
                value="{{ $dueDateStats['active'] ?? 0 }}" 
                icon="bi bi-check-circle"
                change="In good standing"
                trend="up"
                href="{{ route('admin.dashboard.details', 'active') }}"
            />
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Renewal Open" 
                value="{{ $renewalStatusStats['open'] ?? 0 }}" 
                icon="bi bi-arrow-repeat"
                change="In renewal window"
                trend="neutral"
                href="{{ route('admin.dashboard.details', 'renewal-open') }}"
            />
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Due This Week" 
                value="{{ $dueDateStats['due_this_week'] ?? 0 }}" 
                icon="bi bi-calendar-week"
                change="Expiring soon"
                :trend="($dueDateStats['due_this_week'] ?? 0) > 0 ? 'down' : 'neutral'"
                href="{{ route('admin.dashboard.details', 'due-this-week') }}"
            />
        </div>
    </div>

    <!-- New Users and Stores Stats -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="New Users (This Month)" 
                value="{{ $newUsersStats['this_month'] ?? 0 }}" 
                icon="bi bi-person-plus"
                change="Total: {{ $newUsersStats['total'] ?? 0 }} clients"
                trend="up"
                variant="primary"
                href="{{ route('admin.dashboard.details', 'new-users') }}"
            />
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="New Stores (This Month)" 
                value="{{ $newStoresStats['this_month'] ?? 0 }}" 
                icon="bi bi-shop"
                change="Total: {{ $newStoresStats['total'] ?? 0 }} stores"
                trend="up"
                variant="gold"
                href="{{ route('admin.dashboard.details', 'new-stores') }}"
            />
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Due This Month" 
                value="{{ $dueDateStats['due_this_month'] ?? 0 }}" 
                icon="bi bi-calendar-event"
                change="Expiring within 30 days"
                :trend="($dueDateStats['due_this_month'] ?? 0) > 0 ? 'down' : 'neutral'"
                href="{{ route('admin.dashboard.details', 'due-this-month') }}"
            />
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <x-stats-card 
                title="Renewal Expired" 
                value="{{ $renewalStatusStats['expired'] ?? 0 }}" 
                icon="bi bi-x-circle"
                change="Needs attention"
                :trend="($renewalStatusStats['expired'] ?? 0) > 0 ? 'down' : 'up'"
                href="{{ route('admin.dashboard.details', 'renewal-expired') }}"
            />
        </div>
    </div>
    @endif

    @if(Auth::user()->hasPermission('view-the-renewal-status-billing-status-due-date-distribution'))
    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Renewal Status Chart -->
        <div class="col-12 col-md-4">
            <x-card title="Renewal Status" icon="bi bi-pie-chart">
                <div class="chart-container">
                    <canvas id="renewalStatusChart"></canvas>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><span class="badge bg-warning me-2">&nbsp;</span> Open</span>
                        <strong>{{ $renewalStatusStats['open'] ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><span class="badge bg-secondary me-2">&nbsp;</span> Closed</span>
                        <strong>{{ $renewalStatusStats['closed'] ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><span class="badge bg-danger me-2">&nbsp;</span> Expired</span>
                        <strong>{{ $renewalStatusStats['expired'] ?? 0 }}</strong>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Billing Status Chart -->
        <div class="col-12 col-md-4">
            <x-card title="Billing Status" icon="bi bi-credit-card">
                <div class="chart-container">
                    <canvas id="billingStatusChart"></canvas>
                </div>
                <div class="mt-3 small">
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Paid</span>
                                <strong class="text-success">{{ $billingStatusStats['paid'] ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Open</span>
                                <strong class="text-warning">{{ $billingStatusStats['open'] ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Draft</span>
                                <strong class="text-info">{{ $billingStatusStats['pending'] ?? 0 }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Overridden</span>
                                <strong class="text-muted">{{ $billingStatusStats['overridden'] ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Cancelled</span>
                                <strong class="text-secondary">{{ $billingStatusStats['closed'] ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Due Date Distribution Chart -->
        <div class="col-12 col-md-4">
            <x-card title="Due Date Distribution" icon="bi bi-calendar3">
                <div class="chart-container">
                    <canvas id="dueDateChart"></canvas>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><span class="badge bg-danger me-2">&nbsp;</span> Overdue</span>
                        <strong>{{ $dueDateStats['overdue'] ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><span class="badge bg-warning me-2">&nbsp;</span> This Week</span>
                        <strong>{{ $dueDateStats['due_this_week'] ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><span class="badge bg-info me-2">&nbsp;</span> This Month</span>
                        <strong>{{ $dueDateStats['due_this_month'] ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><span class="badge bg-success me-2">&nbsp;</span> Active (>2 months)</span>
                        <strong>{{ $dueDateStats['active'] ?? 0 }}</strong>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
    @endif

    @if(Auth::user()->hasPermission('payment-renewal-queue'))
    <!-- Renewal Queue Table -->
    <x-card title="Payment / Renewal Queue" icon="bi bi-list-check" :padding="false" class="mb-4">
        @if(Auth::user()->hasPermission('view-the-renewal-status-billing-status-due-date-distribution'))
        <x-slot:actions>
            <x-button type="button" variant="outline" size="sm" icon="bi bi-funnel" data-bs-toggle="collapse" data-bs-target="#filterSection">
                Filters
            </x-button>
        </x-slot:actions>
        @endif

        <!-- Filter Section -->

        <div class="collapse p-3 border-bottom" id="filterSection">
            <form action="{{ route('admin.dashboard') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Expiration From</label>
                        <x-input type="date" name="expiration_from" :value="request('expiration_from')" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Expiration To</label>
                        <x-input type="date" name="expiration_to" :value="request('expiration_to')" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Client</label>
                        <x-select name="client_id">
                            <option value="">All Clients</option>
                            @foreach($clients ?? [] as $client)
                                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Permit Type</label>
                        <x-select name="permit_type">
                            <option value="">All Types</option>
                            @foreach($permitTypes ?? [] as $type)
                                <option value="{{ $type->permit_type }}" {{ request('permit_type') == $type->permit_type ? 'selected' : '' }}>
                                    {{ $type->permit_type }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Permit Subtype</label>
                        <x-select name="permit_subtype">
                            <option value="">All Subtypes</option>
                            @foreach($permitSubTypes ?? [] as $subtype)
                                <option value="{{ $subtype->name }}" {{ request('permit_subtype') == $subtype->name ? 'selected' : '' }}>
                                    {{ $subtype->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Payment Status</label>
                        <x-select name="payment_status">
                            <option value="">All Status</option>
                            <option value="draft" {{ request('payment_status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="open" {{ request('payment_status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="overridden" {{ request('payment_status') == 'overridden' ? 'selected' : '' }}>Overridden</option>
                        </x-select>
                    </div>
                    @if(Auth::user()->Role->name === 'Admin')
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Assigned Agent</label>
                            <x-select name="assigned_agent_id">
                                <option value="">All Agents</option>
                                @foreach($agents ?? [] as $agent)
                                    <option value="{{ $agent->id }}" {{ request('assigned_agent_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
                    @endif
                    <div class="col-12">
                        <x-button type="submit" variant="primary" icon="bi bi-search">Apply Filters</x-button>
                        <x-button href="{{ route('admin.dashboard') }}" variant="outline">Clear Filters</x-button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <x-table>
            <x-slot:head>
                <tr>
                    <th>Invoice #</th>
                    @if(in_array(Auth::user()->Role->name, ['Admin', 'Agent']))
                    <th>Store Name</th>
                    @endif
                    <th>Permit Type</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    @if(in_array(Auth::user()->Role->name, ['Admin', 'Agent']))
                    <th>Handled By</th>
                    @endif
                    <th>Created</th>
                    <th class="text-center">Actions</th>
                </tr>
            </x-slot:head>
            
            @forelse($renewalQueue ?? [] as $payment)
                @php
                    $license = $payment->license;
                    $handledBy = $payment->assignedAgent ?? $payment->creator;
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('admin.licenses.payments.show', $license) }}" class="fw-medium text-primary text-decoration-none">
                            {{ $payment->invoice_number }}
                        </a>
                    </td>
                    @if(in_array(Auth::user()->Role->name, ['Admin', 'Agent']))
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <x-avatar :name="$license->store_name ?? 'N/A'" size="sm" />
                            <div>
                                <div class="fw-medium">{{ $license->store_name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    @endif
                    <td>
                        <div>{{ $license->permit_type }}</div>
                        @if($license->permit_subtype)
                            <small class="text-muted">{{ $license->permit_subtype }}</small>
                        @endif
                    </td>
                    <td>
                        <div class="fw-medium">${{ number_format($payment->total_amount, 2) }}</div>
                    </td>
                    <td>
                        @switch($payment->status)
                            @case('paid')
                                <x-badge type="success">Paid</x-badge>
                                @break
                            @case('open')
                                <x-badge type="warning">Open</x-badge>
                                @break
                            @case('draft')
                                <x-badge type="info">Draft</x-badge>
                                @break
                            @case('overridden')
                                <x-badge type="dark">Overridden</x-badge>
                                @break
                            @case('cancelled')
                                <x-badge type="danger">Cancelled</x-badge>
                                @break
                            @default
                                <x-badge type="secondary">{{ ucfirst($payment->status) }}</x-badge>
                        @endswitch
                    </td>
                    @if(in_array(Auth::user()->Role->name, ['Admin', 'Agent']))
                    <td>
                        @if($handledBy)
                            <div class="d-flex align-items-center gap-2">
                                <x-avatar :name="$handledBy->name" size="sm" variant="gold" />
                                <span>{{ $handledBy->name }}</span>
                            </div>
                        @else
                            <span class="text-muted">Unassigned</span>
                        @endif
                    </td>
                    @endif
                    <td>
                        <div class="fw-medium">{{ $payment->created_at->format('M d, Y') }}</div>
                        <small class="text-muted">{{ $payment->created_at->diffForHumans() }}</small>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <x-button href="{{ route('admin.licenses.show', $license) }}" variant="outline-primary" size="sm" icon="bi bi-eye" title="View License"></x-button>
                            <x-button href="{{ route('admin.licenses.payments.show', $license) }}" variant="outline-success" size="sm" icon="bi bi-credit-card" title="Payment"></x-button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ in_array(Auth::user()->Role->name, ['Admin', 'Agent']) ? 8 : 6 }}" class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                        <p class="text-muted mb-0">No payments found in the renewal queue.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if(isset($renewalQueue) && $renewalQueue->hasPages())
            <div class="p-3 border-top">
                {{ $renewalQueue->withQueryString()->links() }}
            </div>
        @endif
    </x-card>
    @endif

    @if(Auth::user()->hasPermission('view-workflow-statuses'))
    <!-- Workflow Status Overview -->
    <x-card title="Workflow Status Overview" icon="bi bi-diagram-3" class="mb-4">
        <div class="row g-3">
            <div class="col-6 col-md-4">
                <div class="text-center p-3 rounded stat-card" style="background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);">
                    <div class="fs-3 fw-bold text-warning">{{ $workflowStatusStats['pending_validation'] ?? 0 }}</div>
                    <small class="text-muted">Pending Validation</small>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="text-center p-3 rounded stat-card" style="background: linear-gradient(135deg, #cce5ff 0%, #b8daff 100%);">
                    <div class="fs-3 fw-bold text-info">{{ $workflowStatusStats['requirements_pending'] ?? 0 }}</div>
                    <small class="text-muted">Requirements Pending</small>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="text-center p-3 rounded stat-card" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);">
                    <div class="fs-3 fw-bold text-success">{{ $workflowStatusStats['approved'] ?? 0 }}</div>
                    <small class="text-muted">Approved</small>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="text-center p-3 rounded stat-card" style="background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%);">
                    <div class="fs-3 fw-bold text-secondary">{{ $workflowStatusStats['active'] ?? 0 }}</div>
                    <small class="text-muted">Active</small>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="text-center p-3 rounded stat-card" style="background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);">
                    <div class="fs-3 fw-bold text-primary">{{ $workflowStatusStats['payment_pending'] ?? 0 }}</div>
                    <small class="text-muted">Payment Pending</small>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="text-center p-3 rounded stat-card" style="background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);">
                    <div class="fs-3 fw-bold text-danger">{{ ($workflowStatusStats['rejected'] ?? 0) + ($workflowStatusStats['expired'] ?? 0) }}</div>
                    <small class="text-muted">Rejected/Expired</small>
                </div>
            </div>
        </div>
    </x-card>
    @endif

@endsection

@push('scripts')
@if(Auth::user()->hasPermission('view-the-renewal-status-billing-status-due-date-distribution'))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Renewal Status Chart
    const renewalCtx = document.getElementById('renewalStatusChart');
    if (renewalCtx) {
        new Chart(renewalCtx, {
            type: 'doughnut',
            data: {
                labels: ['Open', 'Closed', 'Expired'],
                datasets: [{
                    data: [
                        {{ $renewalStatusStats['open'] ?? 0 }},
                        {{ $renewalStatusStats['closed'] ?? 0 }},
                        {{ $renewalStatusStats['expired'] ?? 0 }}
                    ],
                    backgroundColor: ['#ffc107', '#6c757d', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '60%'
            }
        });
    }

    // Billing Status Chart
    const billingCtx = document.getElementById('billingStatusChart');
    if (billingCtx) {
        new Chart(billingCtx, {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Open', 'Draft', 'Overridden', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $billingStatusStats['paid'] ?? 0 }},
                        {{ $billingStatusStats['open'] ?? 0 }},
                        {{ $billingStatusStats['pending'] ?? 0 }},
                        {{ $billingStatusStats['overridden'] ?? 0 }},
                        {{ $billingStatusStats['closed'] ?? 0 }}
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#6c757d', '#adb5bd'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '60%'
            }
        });
    }

    // Due Date Chart
    const dueDateCtx = document.getElementById('dueDateChart');
    if (dueDateCtx) {
        new Chart(dueDateCtx, {
            type: 'bar',
            data: {
                labels: ['Overdue', 'This Week', 'This Month', 'Active'],
                datasets: [{
                    label: 'Licenses',
                    data: [
                        {{ $dueDateStats['overdue'] ?? 0 }},
                        {{ $dueDateStats['due_this_week'] ?? 0 }},
                        {{ $dueDateStats['due_this_month'] ?? 0 }},
                        {{ $dueDateStats['active'] ?? 0 }}
                    ],
                    backgroundColor: ['#dc3545', '#ffc107', '#17a2b8', '#28a745'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>
@endif
@endpush
