@extends('layouts.index')

@section('title', 'View Permit Sub Type')

@section('content')
    <x-page-header title="View Permit Sub Type" subtitle="Sub type details.">
        <x-button href="{{ route('admin.permit-sub-types.index') }}" variant="outline" icon="bi bi-arrow-left">Back</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-6">
            <x-card title="Sub Type Details">
                <table class="table table-borderless">
                    <tr>
                        <th>Name:</th>
                        <td>{{ $subType->name }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($subType->is_active)
                                <x-badge variant="success">Active</x-badge>
                            @else
                                <x-badge variant="danger">Inactive</x-badge>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $subType->created_at->format('M d, Y') }}</td>
                    </tr>
                </table>
            </x-card>
        </div>
    </div>
@endsection
