@extends('layouts.index')

@section('title', 'Create Permit Type')

@section('content')
    <x-page-header title="Create Permit Type" subtitle="Add a new permit type to the system.">
        <x-button href="{{ route('admin.permit-types.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Permit Types</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-8">
            <x-card title="Permit Type Information" icon="bi bi-file-earmark-plus">
                <form action="{{ route('admin.permit-types.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="permit_type" class="form-label">Permit Type Name <span class="text-danger">*</span></label>
                        <x-input name="permit_type" placeholder="Enter permit type name" value="{{ old('permit_type') }}" required />
                        @error('permit_type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <x-checkbox name="is_active" value="1" :checked="old('is_active', true)" label="Active" />
                    </div>

                    <div class="d-flex gap-2">
                        <x-button type="submit" variant="gold" icon="bi bi-check-lg">Create Permit Type</x-button>
                        <x-button href="{{ route('admin.permit-types.index') }}" variant="outline">Cancel</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
