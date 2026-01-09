@extends('layouts.index')

@section('title', 'Modules Management')

@section('content')
    <x-page-header title="Modules Management" subtitle="Manage system modules and navigation.">
        <x-button href="{{ route('admin.modules.create') }}" variant="gold" icon="bi bi-plus-lg">Add New Module</x-button>
    </x-page-header>

    @if(session('success'))
        <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="danger" dismissible>{{ session('error') }}</x-alert>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <x-card title="All Modules" icon="bi bi-grid-3x3-gap" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>Order</th>
                            <th>Module</th>
                            <th>Slug</th>
                            <th>Route</th>
                            <th>Parent</th>
                            <th>Roles</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </x-slot:head>

                    @forelse ($modules as $module)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">{{ $module->order }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($module->icon)
                                        <i class="{{ $module->icon }}"></i>
                                    @endif
                                    <div>
                                        <strong>{{ $module->name }}</strong>
                                        @if($module->is_coming_soon)
                                            <span class="text-danger small">(Coming Soon)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td><code>{{ $module->slug }}</code></td>
                            <td>
                                @if($module->route)
                                    <code class="small">{{ $module->route }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($module->parent)
                                    <x-badge variant="info">{{ $module->parent->name }}</x-badge>
                                @else
                                    <span class="text-muted">Root</span>
                                @endif
                            </td>
                            <td>
                                <x-badge variant="secondary">{{ $module->roles_count }} roles</x-badge>
                            </td>
                            <td>
                                @if($module->is_active)
                                    <x-badge variant="success">Active</x-badge>
                                @else
                                    <x-badge variant="danger">Inactive</x-badge>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.modules.show', $module) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.modules.destroy', $module) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this module?')">
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
                            <td colspan="8" class="text-center py-4">
                                <x-empty-state 
                                    icon="bi bi-grid-3x3-gap" 
                                    title="No modules found" 
                                    description="Get started by creating a new module."
                                />
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>
        </div>
    </div>
@endsection
