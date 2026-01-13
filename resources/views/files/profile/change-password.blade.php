@extends('layouts.index')

@section('title', 'Change Password')

@section('content')
    <x-page-header title="Change Password" subtitle="Update your account password for security.">
        <x-button href="{{ route('admin.profile') }}" variant="outline" icon="bi bi-arrow-left">Back to Profile</x-button>
    </x-page-header>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-6">
            <x-card title="Update Password" icon="bi bi-key">
                <form action="{{ route('admin.profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                        <x-input type="password" name="current_password" placeholder="Enter your current password" required />
                        @error('current_password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                        <x-input type="password" name="password" placeholder="Enter new password" required />
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <x-input type="password" name="password_confirmation" placeholder="Confirm new password" required />
                    </div>

                    <div class="d-flex gap-2">
                        <x-button type="submit" variant="gold" icon="bi bi-check-lg">Update Password</x-button>
                        <x-button href="{{ route('admin.profile') }}" variant="outline">Cancel</x-button>
                    </div>
                </form>
            </x-card>
        </div>

        <div class="col-lg-6">
            <x-card title="Password Requirements" icon="bi bi-info-circle">
                <div class="alert alert-light border mb-0">
                    <h6 class="fw-semibold mb-3"><i class="bi bi-shield-check text-primary me-2"></i>Your password must contain:</h6>
                    <ul class="mb-0 ps-3">
                        <li class="mb-2"><span class="text-muted">At least 8 characters</span></li>
                        <li class="mb-2"><span class="text-muted">A mix of uppercase and lowercase letters</span></li>
                        <li class="mb-2"><span class="text-muted">At least one number</span></li>
                        <li><span class="text-muted">At least one special character (!@#$%^&*)</span></li>
                    </ul>
                </div>
            </x-card>

            <x-card title="Security Tips" icon="bi bi-lightbulb" class="mt-4">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                        <span class="text-muted">Never share your password with anyone</span>
                    </li>
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                        <span class="text-muted">Use a unique password for this account</span>
                    </li>
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                        <span class="text-muted">Avoid using personal information in passwords</span>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                        <span class="text-muted">Change your password regularly</span>
                    </li>
                </ul>
            </x-card>
        </div>
    </div>
@endsection
