@extends('layouts.index')

@section('title', 'Edit User')

@section('content')
    <x-page-header title="Edit User" subtitle="Update user information.">
        <x-button href="{{ route('admin.users.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Users</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-8">
            <x-card title="User Information" icon="bi bi-person-check">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <x-input name="name" placeholder="Enter full name" value="{{ old('name', $user->name) }}" required />
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <x-input type="email" name="email" placeholder="Enter email address" value="{{ old('email', $user->email) }}" required />
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_no" class="form-label">Contact Number</label>
                            <x-input name="contact_no" placeholder="Enter contact number" value="{{ old('contact_no', $user->contact_no) }}" />
                            @error('contact_no')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                            <x-select name="role_id" placeholder="Select Role">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
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
                            <label for="password" class="form-label">Password <small class="text-muted">(Leave blank to keep current)</small></label>
                            <x-input type="password" name="password" placeholder="Enter new password" />
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <x-input type="password" name="password_confirmation" placeholder="Confirm new password" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-checkbox name="is_active" value="1" :checked="old('is_active', $user->is_active)" label="Active (User can login)" />
                    </div>

                    <div class="d-flex gap-2">
                        <x-button type="submit" variant="gold" icon="bi bi-check-lg">Update User</x-button>
                        <x-button href="{{ route('admin.users.index') }}" variant="outline">Cancel</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
