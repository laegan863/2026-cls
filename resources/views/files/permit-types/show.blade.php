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
    </div>
@endsection
