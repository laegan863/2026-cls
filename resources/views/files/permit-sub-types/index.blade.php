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
                                    <a href="{{ route('admin.permit-sub-types.show', $sub) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.permit-sub-types.edit', $sub) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.permit-sub-types.destroy', $sub) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this sub type?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
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
