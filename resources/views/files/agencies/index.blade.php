@extends('layouts.index')

@section('title', 'Agency Management')

@section('content')
    <x-page-header title="Agency Management" subtitle="Manage government agencies and regulatory bodies.">
        <x-button href="{{ route('admin.agency.create') }}" variant="gold" icon="bi bi-plus-lg">Add New Agency</x-button>
    </x-page-header>

    @if(session('success'))
        <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="danger" dismissible>{{ session('error') }}</x-alert>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <x-card title="All Agencies" icon="bi bi-building" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>#</th>
                            <th>Agency Name</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </x-slot:head>

                    @forelse ($agencies as $index => $agency)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $agency->name }}</strong></td>
                            <td>
                                @if($agency->is_active)
                                    <x-badge variant="success">Active</x-badge>
                                @else
                                    <x-badge variant="danger">Inactive</x-badge>
                                @endif
                            </td>
                            <td>{{ $agency->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <x-button href="{{ route('admin.agency.edit', $agency) }}" variant="outline-warning" size="sm" icon="bi bi-pencil" title="Edit"></x-button>
                                    <form action="{{ route('admin.agency.destroy', $agency) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this agency?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="outline-danger" size="sm" icon="bi bi-trash" title="Delete"></x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <x-empty-state 
                                    icon="bi bi-building" 
                                    title="No agencies found" 
                                    description="Get started by creating a new agency."
                                />
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>
        </div>
    </div>
@endsection
