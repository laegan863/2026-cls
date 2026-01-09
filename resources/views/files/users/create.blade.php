@extends('layouts.index')

@section('title', 'Create User')

@section('content')
    <x-page-header title="Create User" subtitle="Add a new user to the system.">
        <x-button href="{{ route('admin.users.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Users</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-12">
            <x-card title="User Information" icon="bi bi-person-plus">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <x-input name="name" placeholder="Enter full name" value="{{ old('name') }}" required />
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <x-input type="email" name="email" placeholder="Enter email address" value="{{ old('email') }}" required />
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_no" class="form-label">Contact Number</label>
                            <x-input name="contact_no" placeholder="Enter contact number" value="{{ old('contact_no') }}" />
                            @error('contact_no')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                            <x-select name="role_id" placeholder="Select Role">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            @error('role_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <x-input type="password" name="password" placeholder="Enter password" required />
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <x-input type="password" name="password_confirmation" placeholder="Confirm password" required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-checkbox name="is_active" value="1" :checked="old('is_active', true)" label="Active (User can login)" />
                    </div>

                    <div class="d-flex gap-2">
                        <x-button type="submit" variant="gold" icon="bi bi-check-lg">Create User</x-button>
                        <x-button href="{{ route('admin.users.index') }}" variant="outline">Cancel</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
