@extends('layouts.index')

@section('title', 'Create Permission')

@section('content')
    <x-page-header title="Create Permission" subtitle="Add a new permission to the system.">
        <x-button href="{{ route('admin.permissions.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Permissions</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-8">
            <x-card title="Permission Information" icon="bi bi-key-fill">
                <form action="{{ route('admin.permissions.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                        <x-input name="name" placeholder="e.g., Create Users, View Reports" value="{{ old('name') }}" required />
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="module" class="form-label">Module</label>
                        <x-input name="module" placeholder="e.g., Users, Reports, Settings" value="{{ old('module') }}" />
                        <small class="text-muted">Group permissions by module for easier management.</small>
                        @error('module')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <x-textarea name="description" placeholder="Enter permission description" rows="3">{{ old('description') }}</x-textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <x-checkbox name="is_active" value="1" :checked="old('is_active', true)" label="Active" />
                    </div>

                    <div class="d-flex gap-2">
                        <x-button type="submit" variant="gold" icon="bi bi-check-lg">Create Permission</x-button>
                        <x-button href="{{ route('admin.permissions.index') }}" variant="outline">Cancel</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
