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
        <div class="col-lg-6">
            <x-card title="Permit Type Information" icon="bi bi-file-earmark-text">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Permit Type:</th>
                        <td>{{ $permitType->permit_type }}</td>
                    </tr>
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
        </div>

        <div class="col-lg-6">
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
