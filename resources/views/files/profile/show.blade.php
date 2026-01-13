@extends('layouts.index')

@section('title', 'My Profile')

@section('content')
    <!-- Breadcrumb -->
    <x-breadcrumb class="mb-4">
        <x-breadcrumb-item href="{{ route('admin.dashboard') }}">Dashboard</x-breadcrumb-item>
        <x-breadcrumb-item active>My Profile</x-breadcrumb-item>
    </x-breadcrumb>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Profile Header Card -->
    <div class="profile-header mb-4 animate-slide-up">
        <div class="profile-cover">
            <div class="profile-cover-actions">
                <x-button href="{{ route('admin.profile.edit') }}" variant="outline" size="sm" style="background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(10px); border: none;">
                    <i class="bi bi-pencil me-1"></i> Edit Profile
                </x-button>
            </div>
        </div>
        <div class="profile-info-section">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end gap-4">
                        <div class="profile-avatar-wrapper">
                            <div class="profile-avatar">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=200&background=d4a94c&color=1a2b4a&bold=true&font-size=0.4" alt="{{ $user->name }}">
                            </div>
                            <span class="profile-avatar-badge">
                                <i class="bi bi-check-lg"></i>
                            </span>
                        </div>
                        <div class="text-center text-md-start">
                            <h1 class="profile-name">{{ $user->name }}</h1>
                            <p class="profile-title"><i class="bi bi-shield-check me-2"></i>{{ $user->role->name ?? 'User' }}</p>
                            <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 mt-2">
                                <span class="d-flex align-items-center gap-1" style="color: rgba(255,255,255,0.7); font-size: 0.875rem;">
                                    <i class="bi bi-envelope"></i> {{ $user->email }}
                                </span>
                                <span class="d-flex align-items-center gap-1" style="color: rgba(255,255,255,0.7); font-size: 0.875rem;">
                                    <i class="bi bi-calendar3"></i> Joined {{ $user->created_at->format('F Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="profile-stats justify-content-center justify-content-lg-end">
                        <div class="profile-stat">
                            <div class="profile-stat-value">
                                @if($user->is_active)
                                    <span class="text-success"><i class="bi bi-check-circle-fill"></i></span>
                                @else
                                    <span class="text-danger"><i class="bi bi-x-circle-fill"></i></span>
                                @endif
                            </div>
                            <div class="profile-stat-label">Status</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-4">
            <!-- Account Information Card -->
            <x-card title="Account Information" icon="bi bi-person" class="mb-4">
                <x-slot:actions>
                    <x-icon-button href="{{ route('admin.profile.edit') }}" icon="bi bi-pencil" variant="outline" size="sm" title="Edit Profile" />
                </x-slot:actions>
                
                <div class="profile-about-item">
                    <x-icon name="bi bi-person" variant="muted" />
                    <div>
                        <div class="label">Full Name</div>
                        <div class="value">{{ $user->name }}</div>
                    </div>
                </div>
                
                <div class="profile-about-item">
                    <x-icon name="bi bi-envelope" variant="muted" />
                    <div>
                        <div class="label">Email Address</div>
                        <div class="value">{{ $user->email }}</div>
                    </div>
                </div>
                
                <div class="profile-about-item">
                    <x-icon name="bi bi-telephone" variant="muted" />
                    <div>
                        <div class="label">Contact Number</div>
                        <div class="value">{{ $user->contact_no ?? 'Not provided' }}</div>
                    </div>
                </div>
                
                <div class="profile-about-item">
                    <x-icon name="bi bi-shield-check" variant="muted" />
                    <div>
                        <div class="label">Role</div>
                        <div class="value">{{ $user->role->name ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="profile-about-item">
                    <x-icon name="bi bi-calendar3" variant="muted" />
                    <div>
                        <div class="label">Member Since</div>
                        <div class="value">{{ $user->created_at->format('F d, Y') }}</div>
                    </div>
                </div>
                
                <div class="profile-about-item">
                    <x-icon name="bi bi-clock" variant="muted" />
                    <div>
                        <div class="label">Last Updated</div>
                        <div class="value">{{ $user->updated_at->format('F d, Y h:i A') }}</div>
                    </div>
                </div>
            </x-card>

            <!-- Account Status Card -->
            <x-card title="Account Status" icon="bi bi-shield" class="mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    @if($user->is_active)
                        <span class="badge bg-success-subtle text-success px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i> Active
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger px-3 py-2">
                            <i class="bi bi-x-circle me-1"></i> Inactive
                        </span>
                    @endif
                </div>
                <p class="text-muted small mb-0">
                    Your account is currently {{ $user->is_active ? 'active and in good standing' : 'inactive. Please contact an administrator.' }}.
                </p>
            </x-card>
        </div>

        <!-- Right Column -->
        <div class="col-lg-8">
            <!-- Quick Actions Card -->
            <x-card title="Quick Actions" icon="bi bi-lightning" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('admin.profile.edit') }}" class="text-decoration-none">
                            <div class="border rounded p-4 text-center hover-shadow transition-all h-100" style="cursor: pointer;">
                                <div class="mb-3">
                                    <i class="bi bi-pencil-square fs-1 text-primary"></i>
                                </div>
                                <h5 class="fw-semibold mb-1" style="color: var(--bs-primary);">Edit Profile</h5>
                                <p class="text-muted small mb-0">Update your personal information</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.profile.password') }}" class="text-decoration-none">
                            <div class="border rounded p-4 text-center hover-shadow transition-all h-100" style="cursor: pointer;">
                                <div class="mb-3">
                                    <i class="bi bi-key fs-1 text-gold"></i>
                                </div>
                                <h5 class="fw-semibold mb-1" style="color: var(--bs-primary);">Change Password</h5>
                                <p class="text-muted small mb-0">Update your account password</p>
                            </div>
                        </a>
                    </div>
                </div>
            </x-card>

            <!-- Security Settings -->
            <x-card title="Security Information" icon="bi bi-shield-lock">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="ps-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary-subtle p-2">
                                            <i class="bi bi-key text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">Password</div>
                                            <small class="text-muted">Last changed: {{ $user->updated_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-0">
                                    <x-button href="{{ route('admin.profile.password') }}" variant="outline" size="sm">Change</x-button>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-success-subtle p-2">
                                            <i class="bi bi-envelope-check text-success"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">Email Verified</div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-0">
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-check me-1"></i> Verified
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-2px);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endpush
