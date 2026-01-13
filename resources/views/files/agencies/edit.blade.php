@extends('layouts.index')

@section('title', 'Edit Agency')

@section('content')
    <x-page-header title="Edit Agency" subtitle="Update agency information.">
        <x-button href="{{ route('admin.agency.index') }}" variant="outline-secondary" icon="bi bi-arrow-left">Back to List</x-button>
    </x-page-header>

    @if($errors->any())
        <x-alert type="danger" dismissible>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <div class="row">
        <div class="col-lg-6">
            <x-card title="Agency Information" icon="bi bi-building">
                <form action="{{ route('admin.agency.update', $agency) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Agency Name <span class="text-danger">*</span></label>
                        <x-input name="name" type="text" placeholder="Enter agency name" value="{{ old('name', $agency->name) }}" required />
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $agency->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        <small class="text-muted">Inactive agencies will not appear in selection dropdowns.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <x-button type="submit" variant="gold" icon="bi bi-check-lg">Update Agency</x-button>
                        <x-button href="{{ route('admin.agency.index') }}" variant="outline-secondary">Cancel</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
