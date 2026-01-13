@extends('layouts.index')

@section('title', 'Permit Sub Types')

@section('content')
    <x-page-header title="Permit Sub Types" subtitle="Manage permit sub types.">
        <x-button href="{{ route('admin.permit-sub-types.create') }}" variant="gold" icon="bi bi-plus-lg">Add New</x-button>
    </x-page-header>

    @if(session('success'))
        <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <x-card title="All Permit Sub Types" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </x-slot:head>

                    @forelse($subTypes as $i => $sub)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $sub->name }}</td>
                            <td>
                                @if($sub->is_active)
                                    <x-badge variant="success">Active</x-badge>
                                @else
                                    <x-badge variant="danger">Inactive</x-badge>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <x-button href="{{ route('admin.permit-sub-types.show', $sub) }}" variant="outline-info" size="sm" icon="bi bi-eye" title="View"></x-button>
                                    <x-button href="{{ route('admin.permit-sub-types.edit', $sub) }}" variant="outline-warning" size="sm" icon="bi bi-pencil" title="Edit"></x-button>
                                    <form action="{{ route('admin.permit-sub-types.destroy', $sub) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this sub type?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="outline-danger" size="sm" icon="bi bi-trash" title="Delete"></x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No sub types yet. <a href="{{ route('admin.permit-sub-types.create') }}">Create one</a></td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>
        </div>
    </div>
@endsection
