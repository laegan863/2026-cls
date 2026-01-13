@extends('layouts.index')

@section('title', 'Edit Profile')

@section('content')
    <x-page-header title="Edit Profile" subtitle="Update your personal information.">
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
        <div class="col-lg-8">
            <x-card title="Profile Information" icon="bi bi-person-check">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <x-input name="name" placeholder="Enter your full name" value="{{ old('name', $user->name) }}" required />
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <x-input type="email" name="email" placeholder="Enter your email address" value="{{ old('email', $user->email) }}" required />
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_no" class="form-label">Contact Number</label>
                            <x-input name="contact_no" placeholder="Enter your contact number" value="{{ old('contact_no', $user->contact_no) }}" />
                            @error('contact_no')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <x-input value="{{ $user->role->name ?? 'N/A' }}" readonly disabled />
                            <small class="text-muted">Contact an administrator to change your role.</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <x-button type="submit" variant="gold" icon="bi bi-check-lg">Save Changes</x-button>
                        <x-button href="{{ route('admin.profile') }}" variant="outline">Cancel</x-button>
                    </div>
                </form>
            </x-card>
        </div>

        <div class="col-lg-4">
            <!-- Profile Preview -->
            <x-card title="Profile Preview" icon="bi bi-eye" class="mb-4">
                <div class="text-center mb-4">
                    <div class="d-inline-block position-relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=120&background=d4a94c&color=1a2b4a&bold=true" 
                             alt="{{ $user->name }}" 
                             class="rounded-circle"
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <h5 class="mt-3 mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-0">{{ $user->role->name ?? 'User' }}</p>
                </div>
            </x-card>

            <!-- Quick Links -->
            <x-card title="Account Settings" icon="bi bi-gear">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.profile') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-person me-3 text-muted"></i>
                        <span>View Profile</span>
                    </a>
                    <a href="{{ route('admin.profile.password') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-key me-3 text-muted"></i>
                        <span>Change Password</span>
                    </a>
                </div>
            </x-card>
        </div>
    </div>
@endsection
