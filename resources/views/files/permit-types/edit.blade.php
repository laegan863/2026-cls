@extends('layouts.index')

@section('title', 'Edit Permit Type')

@section('content')
    <x-page-header title="Edit Permit Type" subtitle="Update permit type information.">
        <x-button href="{{ route('admin.permit-types.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Permit Types</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-8">
            <x-card title="Permit Type Information" icon="bi bi-file-earmark-check">
                <form action="{{ route('admin.permit-types.update', $permitType) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="permit_type" class="form-label">Permit Type Name <span class="text-danger">*</span></label>
                        <x-input name="permit_type" placeholder="Enter permit type name" value="{{ old('permit_type', $permitType->permit_type) }}" required />
                        @error('permit_type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <x-checkbox name="is_active" value="1" :checked="old('is_active', $permitType->is_active)" label="Active" />
                    </div>

                    <div class="d-flex gap-2">
                        <x-button type="submit" variant="gold" icon="bi bi-check-lg">Update Permit Type</x-button>
                        <x-button href="{{ route('admin.permit-types.index') }}" variant="outline">Cancel</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
