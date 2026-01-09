@extends('layouts.index')

@section('title', 'Licensing & Permitting')

@section('content')
    {{-- <x-page-header title="Licensing & Permitting" subtitle="Welcome back, John! Here's what's happening today." /> --}}
    <x-page-header title="Licensing & Permitting" subtitle="Welcome back, John! Here's what's happening today.">
        <x-button href="{{ route('admin.add-new-license-user') }}" variant="gold" icon="bi bi-plus-lg">Add New</x-button>
    </x-page-header>
    <div class="row">
        <div class="col-lg-12">
            <x-card title="Licenses users list" icon="fas fa-table" class="mb-4" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </x-slot:head>

                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <x-avatar name="John Doe" size="sm" />
                                <span>John Doe</span>
                            </div>
                        </td>
                        <td>john@example.com</td>
                        <td><x-badge type="primary">Admin</x-badge></td>
                        <td><x-status status="active" /></td>
                        <td>
                            <x-dropdown align="end">
                                <x-slot:trigger>
                                    <x-icon-button icon="fas fa-ellipsis-v" variant="light" size="sm" />
                                </x-slot:trigger>
                                <x-dropdown-item href="#" icon="fas fa-eye">View</x-dropdown-item>
                                <x-dropdown-item href="#" icon="fas fa-edit">Edit</x-dropdown-item>
                                <x-dropdown-divider />
                                <x-dropdown-item href="#" icon="fas fa-trash"
                                    class="text-danger">Delete</x-dropdown-item>
                            </x-dropdown>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <x-avatar name="Jane Smith" size="sm" variant="gold" />
                                <span>Jane Smith</span>
                            </div>
                        </td>
                        <td>jane@example.com</td>
                        <td><x-badge type="gold">Manager</x-badge></td>
                        <td><x-status status="active" /></td>
                        <td>
                            <x-dropdown align="end">
                                <x-slot:trigger>
                                    <x-icon-button icon="fas fa-ellipsis-v" variant="light" size="sm" />
                                </x-slot:trigger>
                                <x-dropdown-item href="#" icon="fas fa-eye">View</x-dropdown-item>
                                <x-dropdown-item href="#" icon="fas fa-edit">Edit</x-dropdown-item>
                                <x-dropdown-divider />
                                <x-dropdown-item href="#" icon="fas fa-trash"
                                    class="text-danger">Delete</x-dropdown-item>
                            </x-dropdown>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <x-avatar name="Bob Wilson" size="sm" />
                                <span>Bob Wilson</span>
                            </div>
                        </td>
                        <td>bob@example.com</td>
                        <td><x-badge type="light">User</x-badge></td>
                        <td><x-status status="pending" /></td>
                        <td>
                            <x-dropdown align="end">
                                <x-slot:trigger>
                                    <x-icon-button icon="fas fa-ellipsis-v" variant="light" size="sm" />
                                </x-slot:trigger>
                                <x-dropdown-item href="#" icon="fas fa-eye">View</x-dropdown-item>
                                <x-dropdown-item href="#" icon="fas fa-edit">Edit</x-dropdown-item>
                                <x-dropdown-divider />
                                <x-dropdown-item href="#" icon="fas fa-trash"
                                    class="text-danger">Delete</x-dropdown-item>
                            </x-dropdown>
                        </td>
                    </tr>
                </x-table>
            </x-card>
        </div>
    </div>

@endsection
