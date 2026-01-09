@extends('layouts.index')

@section('title', 'Permit Types Management')

@section('content')
    <x-page-header title="Permit Types Management" subtitle="Manage permit types and their sub-types.">
        <x-button href="{{ route('admin.permit-types.create') }}" variant="gold" icon="bi bi-plus-lg">Add New Permit Type</x-button>
    </x-page-header>

    @if(session('success'))
        <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="danger" dismissible>{{ session('error') }}</x-alert>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <x-card title="All Permit Types" icon="bi bi-file-earmark-text" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>#</th>
                            <th>Permit Type</th>
                            <th>Sub Types</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </x-slot:head>

                    @forelse ($permitTypes as $index => $permitType)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $permitType->permit_type }}</strong>
                            </td>
                            <td>
                                @if($permitType->sub_type && count($permitType->sub_type) > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($permitType->sub_type as $subType)
                                            <x-badge variant="secondary">{{ $subType }}</x-badge>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No sub-types</span>
                                @endif
                            </td>
                            <td>
                                @if($permitType->is_active)
                                    <x-badge variant="success">Active</x-badge>
                                @else
                                    <x-badge variant="danger">Inactive</x-badge>
                                @endif
                            </td>
                            <td>{{ $permitType->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.permit-types.show', $permitType) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.permit-types.edit', $permitType) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.permit-types.destroy', $permitType) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this permit type?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <x-empty-state 
                                    icon="bi bi-file-earmark-text" 
                                    title="No permit types found" 
                                    description="Get started by creating a new permit type."
                                />
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>
        </div>
    </div>
@endsection
