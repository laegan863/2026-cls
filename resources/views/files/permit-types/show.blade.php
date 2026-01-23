@extends('layouts.index')

@section('title', 'View Permit Type')

@section('content')
    <x-page-header title="View Permit Type" subtitle="Permit type details.">
        <div class="d-flex gap-2">
            <x-button href="{{ route('admin.permit-types.edit', $permitType) }}" variant="warning" icon="bi bi-pencil">Edit</x-button>
            <x-button href="{{ route('admin.permit-types.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Permit Types</x-button>
        </div>
    </x-page-header>

    <div class="row">
        <div class="col-lg-8">
            <x-card title="Permit Type Information" icon="bi bi-file-earmark-text">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="200">Permit Type:</th>
                        <td>{{ $permitType->permit_type }}</td>
                    </tr>
                    @if($permitType->short_name)
                    <tr>
                        <th>Short Name:</th>
                        <td>{{ $permitType->short_name }}</td>
                    </tr>
                    @endif
                    @if($permitType->description)
                    <tr>
                        <th>Description:</th>
                        <td>{{ $permitType->description }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($permitType->is_active)
                                <x-badge variant="success">Active</x-badge>
                            @else
                                <x-badge variant="danger">Inactive</x-badge>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $permitType->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated:</th>
                        <td>{{ $permitType->updated_at->format('M d, Y h:i A') }}</td>
                    </tr>
                </table>
            </x-card>

            <!-- Jurisdiction Section -->
            <x-card title="Jurisdiction" icon="bi bi-globe" class="mt-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="200">Jurisdiction Level:</th>
                        <td>
                            @if($permitType->jurisdiction_level)
                                <x-badge variant="info">{{ ucfirst($permitType->jurisdiction_level) }}</x-badge>
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Agency Name:</th>
                        <td>{{ $permitType->agency_name ?: 'Not specified' }}</td>
                    </tr>
                </table>
            </x-card>

            <!-- Renewal Settings Section -->
            <x-card title="Renewal Settings" icon="bi bi-arrow-repeat" class="mt-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="200">Renewal Enabled:</th>
                        <td>
                            @if($permitType->has_renewal)
                                <x-badge variant="success">Yes</x-badge>
                            @else
                                <x-badge variant="secondary">No</x-badge>
                            @endif
                        </td>
                    </tr>
                    @if($permitType->has_renewal)
                    <tr>
                        <th>Renewal Cycle:</th>
                        <td>
                            @if($permitType->renewal_cycle_months)
                                {{ $permitType->renewal_cycle_months }} month(s)
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Reminder Emails:</th>
                        <td>
                            @if($permitType->reminder_days && count($permitType->reminder_days) > 0)
                                @foreach($permitType->reminder_days as $days)
                                    <x-badge variant="outline-primary" class="me-1">{{ $days }} days before</x-badge>
                                @endforeach
                            @else
                                <span class="text-muted">No reminders set</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                </table>
            </x-card>

            <!-- Fees Section -->
            <x-card title="Fees Associated" icon="bi bi-currency-dollar" class="mt-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="200">Government Fee:</th>
                        <td>
                            @if($permitType->government_fee)
                                ${{ number_format($permitType->government_fee, 2) }}
                            @else
                                <span class="text-muted">$0.00</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>CLS-360 Service Fee:</th>
                        <td>
                            @if($permitType->cls_service_fee)
                                ${{ number_format($permitType->cls_service_fee, 2) }}
                            @else
                                <span class="text-muted">$0.00</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>City/County Fee:</th>
                        <td>
                            @if($permitType->city_county_fee)
                                ${{ number_format($permitType->city_county_fee, 2) }}
                            @else
                                <span class="text-muted">$0.00</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Additional Fee:</th>
                        <td>
                            @if($permitType->additional_fee)
                                ${{ number_format($permitType->additional_fee, 2) }}
                                @if($permitType->additional_fee_description)
                                    <br><small class="text-muted">{{ $permitType->additional_fee_description }}</small>
                                @endif
                            @else
                                <span class="text-muted">$0.00</span>
                            @endif
                        </td>
                    </tr>
                    <tr class="border-top">
                        <th>Total Fees:</th>
                        <td>
                            <strong>${{ number_format(
                                ($permitType->government_fee ?? 0) + 
                                ($permitType->cls_service_fee ?? 0) + 
                                ($permitType->city_county_fee ?? 0) + 
                                ($permitType->additional_fee ?? 0), 2) }}</strong>
                        </td>
                    </tr>
                </table>
            </x-card>
        </div>

        <div class="col-lg-4">
            <x-card title="Sub-Permits" icon="bi bi-diagram-3">
                @if($permitType->subPermits->count() > 0)
                    <x-table>
                        <x-slot:head>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Status</th>
                            </tr>
                        </x-slot:head>

                        @foreach($permitType->subPermits as $index => $subPermit)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $subPermit->name }}</td>
                                <td>
                                    @if($subPermit->is_active)
                                        <x-badge variant="success">Active</x-badge>
                                    @else
                                        <x-badge variant="danger">Inactive</x-badge>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </x-table>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-diagram-3 text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2 mb-0">No sub-permits for this permit type.</p>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
@endsection
