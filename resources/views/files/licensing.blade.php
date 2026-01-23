@extends('layouts.index')

@section('title', 'Licensing & Permitting')

@section('content')
    @php
        $role = Auth::user()->Role->name;
    @endphp

    <x-page-header title="Licensing & Permitting" subtitle="Welcome back, {{ Auth::user()->name }}! Here's what's happening today.">
        @if($role === 'Admin' || $role === 'Agent')
            <form action="{{ route('admin.licenses.bulk-refresh') }}" method="POST" class="d-inline me-2">
                @csrf
                <x-button type="submit" variant="primary" icon="bi bi-arrow-clockwise">Bulk Refresh Status</x-button>
            </form>
        @endif
        <x-button href="{{ route('admin.licenses.create') }}" variant="gold" icon="bi bi-plus-lg">Add New Store</x-button>
    </x-page-header>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="row">
        <!-- Filter Section -->
            <x-card class="mb-3">
                <form action="{{ route('admin.licenses.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Store Name</label>
                            <x-input type="text" name="store_name" placeholder="Search by Store Name" :value="request('store_name')" />
                        </div>
                        @if($role === 'Admin' || $role === 'Agent')
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Client Name</label>
                            <x-select name="client_id">
                                <option value="">All Clients</option>
                                @php
                                    $clients = \App\Models\User::whereHas('role', function($q) {
                                        $q->where('slug', 'client');
                                    })->orderBy('name')->get();
                                @endphp
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
                        @endif
                        @if($role === 'Admin' || $role === 'Agent')
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Store Status</label>
                            <x-select name="is_active">
                                <option value="">All Stores</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Deactivated</option>
                            </x-select>
                        </div>
                        @endif
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <x-button type="submit" variant="primary" icon="bi bi-search">Filter</x-button>
                                <x-button href="{{ route('admin.licenses.index') }}" variant="outline">Clear</x-button>
                            </div>
                        </div>
                    </div>
                </form>
            </x-card>
        <div class="col-lg-12">
            <x-card title="Store List {{ $role === 'Client' ? '(My Stores)' : '(All Stores & Licenses)' }}" icon="fas fa-store" class="mb-4" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>Store Name</th>
                            <th>Store Address</th>
                            <th>Status</th>
                            <th>Document</th>
                            <th>Actions</th>
                        </tr>
                    </x-slot:head>

                    @forelse($licenses as $license)
                        <tr class="{{ $license->is_active == false ? 'table-secondary opacity-75' : '' }}">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <x-avatar name="{{ $license->store_name ?? 'N/A' }}" size="sm" />
                                    <a href="{{ route('admin.licenses.show', $license) }}">
                                        <span class="text-dark fw-medium">{{ $license->store_name ?? 'N/A' }}</span>
                                    </a>
                                </div>
                            </td>
                            <td>
                                @php
                                    $addressParts = array_filter([
                                        $license->store_address ?? null,
                                        $license->store_city ?? $license->city ?? null,
                                        $license->store_state ?? $license->state ?? null,
                                        $license->store_zip_code ?? $license->zip_code ?? null,
                                    ]);
                                @endphp
                                {{ implode(', ', $addressParts) ?: 'N/A' }}
                            </td>
                            <td>
                                @if($license->is_active == true)
                                    <x-badge type="success">Active</x-badge>
                                @else
                                    <x-badge type="danger">Deactivated</x-badge>
                                @endif
                            </td>
                            <td>
                                @if($license->license_document)
                                    <x-badge type="success"><i class="bi bi-check-circle me-1"></i>Uploaded</x-badge>
                                @elseif(in_array($license->billing_status, ['paid', 'overridden']))
                                    <x-badge type="warning"><i class="bi bi-clock me-1"></i>Pending</x-badge>
                                @else
                                    <x-badge type="secondary"><i class="bi bi-dash me-1"></i>N/A</x-badge>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if(Auth::user()->hasPermission('view'))
                                    <x-icon-button href="{{ route('admin.licenses.show', $license) }}" icon="fas fa-eye" variant="primary" size="sm" title="View Details" />
                                    @endif

                                    @if(Auth::user()->hasPermission('edit'))
                                    <x-icon-button href="{{ route('admin.licenses.edit', $license) }}" icon="fas fa-edit" variant="secondary" size="sm" title="Edit" />
                                    @endif

                                    @if(Auth::user()->Role->name === 'Admin')
                                    <form action="{{ route('admin.licenses.toggle-status', $license) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        @if($license->is_active == true)
                                            <x-icon-button type="submit" icon="fas fa-ban" variant="warning" size="sm" title="Deactivate Store" onclick="return confirm('Are you sure you want to deactivate this store?')" />
                                        @else
                                            <x-icon-button type="submit" icon="fas fa-check-circle" variant="success" size="sm" title="Activate Store" onclick="return confirm('Are you sure you want to activate this store?')" />
                                        @endif
                                    </form>
                                    @endif

                                    @if((Auth::user()->Role->name === 'Admin' || Auth::user()->Role->name === 'Agent') && in_array($license->billing_status, ['paid', 'overridden']) && !$license->license_document)
                                    <x-icon-button type="button" icon="fas fa-upload" variant="success" size="sm" title="Upload Document" data-bs-toggle="modal" data-bs-target="#uploadDocModal{{ $license->id }}" />
                                    @endif

                                    @if(Auth::user()->hasPermission('delete'))
                                    <form action="{{ route('admin.licenses.destroy', $license) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <x-icon-button type="submit" icon="fas fa-trash" variant="danger" size="sm" title="Delete" onclick="return confirm('Are you sure you want to delete this store?')" />
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Upload Document Modal for this license -->
                        @if((Auth::user()->Role->name === 'Admin' || Auth::user()->Role->name === 'Agent') && in_array($license->billing_status, ['paid', 'overridden']) && !$license->license_document)
                        <div class="modal fade" id="uploadDocModal{{ $license->id }}" tabindex="-1" aria-labelledby="uploadDocModalLabel{{ $license->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.licenses.upload-document', $license) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="uploadDocModalLabel{{ $license->id }}">
                                                <i class="bi bi-upload me-2"></i>Upload Document for {{ $license->store_name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Upload the official license document. The client will be able to view and download this document.
                                            </div>
                                            <div class="mb-3">
                                                <label for="license_document_{{ $license->id }}" class="form-label fw-bold">
                                                    <i class="bi bi-file-earmark-arrow-up me-1"></i>Select Document
                                                </label>
                                                <input type="file" 
                                                       class="form-control" 
                                                       id="license_document_{{ $license->id }}" 
                                                       name="license_document"
                                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                                       required>
                                                <div class="form-text">Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max: 10MB)</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <x-button type="button" variant="secondary" data-bs-dismiss="modal">Cancel</x-button>
                                            <x-button type="submit" variant="success" icon="bi bi-upload">Upload Document</x-button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No stores found. <a href="{{ route('admin.licenses.create') }}">Add your first store</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>
        </div>
    </div>
@endsection
