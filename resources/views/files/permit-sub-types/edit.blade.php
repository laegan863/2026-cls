@extends('layouts.index')

@section('title', 'Edit Permit Sub Type')

@section('content')
    <x-page-header title="Edit Permit Sub Type" subtitle="Update sub type.">
        <x-button href="{{ route('admin.permit-sub-types.index') }}" variant="outline" icon="bi bi-arrow-left">Back</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-6">
            <x-card title="Edit Sub Type">
                <form action="{{ route('admin.permit-sub-types.update', $subType) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Sub Type Name <span class="text-danger">*</span></label>
                        <x-input name="name" type="text" value="{{ old('name', $subType->name) }}" required />
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <x-checkbox name="is_active" value="1" :checked="old('is_active', $subType->is_active)" label="Active" />
                    </div>
                    <div class="d-flex gap-2">
                        <x-button type="submit" variant="gold">Update</x-button>
                        <x-button href="{{ route('admin.permit-sub-types.index') }}" variant="outline">Cancel</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
