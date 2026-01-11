@extends('layouts.index')

@section('title', 'Create Permit Sub Type')

@section('content')
    <x-page-header title="Create Permit Sub Type" subtitle="Add a new permit sub type.">
        <x-button href="{{ route('admin.permit-sub-types.index') }}" variant="outline" icon="bi bi-arrow-left">Back</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-6">
            <x-card title="New Sub Type">
                <form action="{{ route('admin.permit-sub-types.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Sub Type Name <span class="text-danger">*</span></label>
                        <x-input name="name" type="text" placeholder="Enter sub type" value="{{ old('name') }}" required />
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <x-checkbox name="is_active" value="1" :checked="old('is_active', true)" label="Active" />
                    </div>
                    <div class="d-flex gap-2">
                        <x-button type="submit" variant="gold">Create</x-button>
                        <x-button href="{{ route('admin.permit-sub-types.index') }}" variant="outline">Cancel</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
