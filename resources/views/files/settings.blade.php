@extends('layouts.index')

@section('title', 'Admin Control Center')

@section('content')
    @php
        $settings = [
            [
                'name' => 'Roles',
                'slug' => 'roles',
                'icon' => 'bi bi-shield-lock',
                'route' => route('admin.roles.index'),
                'description' => 'Manage user roles and their permissions',
            ],
            [
                'name' => 'Permissions',
                'slug' => 'permissions',
                'icon' => 'bi bi-key',
                'route' => route('admin.permissions.index'),
                'description' => 'Manage system permissions',
            ],
            [
                'name' => 'Permit Types',
                'slug' => 'permit-types',
                'icon' => 'bi bi-file-earmark-text',
                'route' => route('admin.permit-types.index'),
                'description' => 'Manage permit types and sub-types',
            ],
            [
                'name' => 'User Management',
                'slug' => 'user-management',
                'icon' => 'bi bi-people',
                'route' => route('admin.users.index'),
                'description' => 'Manage users and their roles',
            ],
        ];
    @endphp

    <x-page-header title="Admin Control Center" subtitle="Manage system settings, user roles, and configurations.">
    </x-page-header>
    <div class="row">
        <div class="col-lg-3">
            <x-card title="Overview" icon="bi bi-gear-fill">
                <p>This section provides an overview of all administrative control activities within the system.
                    Here you can manage settings, user roles, and system configurations.
                </p>
                <hr>
                <ul class="list-unstyled mb-0">
                    @foreach ($settings as $setting)
                        <li class="mb-2">
                            <a href="{{ $setting['route'] }}" class="text-decoration-none">
                                <i class="{{ $setting['icon'] }} me-2"></i>{{ $setting['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </x-card>
        </div>
        <div class="col-lg-9">
            <x-card title="Admin Control Center" icon="fas fa-table" class="mb-4" :padding="false">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th>#</th>
                            <th>Setting Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </x-slot:head>

                    @foreach ($settings as $index => $setting)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($setting['icon'])
                                        <i class="{{ $setting['icon'] }}"></i>
                                    @endif
                                    <strong>{{ $setting['name'] }}</strong>
                                </div>
                            </td>
                            <td>{{ $setting['description'] }}</td>
                            <td>
                                <x-button href="{{ $setting['route'] }}" variant="outline-primary" size="sm" icon="bi bi-eye">Manage</x-button>
                            </td>
                        </tr>
                    @endforeach
                </x-table>
            </x-card>
        </div>
    </div>

@endsection
