@extends('layouts.index')

@section('title', 'Create Module')

@section('content')
    <x-page-header title="Create Module" subtitle="Add a new module to the system.">
        <x-button href="{{ route('admin.modules.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Modules</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-8">
            <x-card title="Module Information" icon="bi bi-grid-3x3-gap-fill">
                <form action="{{ route('admin.modules.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Module Name <span class="text-danger">*</span></label>
                            <x-input name="name" placeholder="Enter module name" value="{{ old('name') }}" required />
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">Slug <small class="text-muted">(auto-generated if empty)</small></label>
                            <x-input name="slug" placeholder="module-slug" value="{{ old('slug') }}" />
                            @error('slug')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="icon" class="form-label">Icon Class</label>
                            <x-input name="icon" placeholder="e.g., bi bi-house, fas fa-cog" value="{{ old('icon') }}" />
                            <small class="text-muted">Use Bootstrap Icons (bi bi-*) or Font Awesome (fas fa-*)</small>
                            @error('icon')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="route" class="form-label">Route Name</label>
                            <x-input name="route" placeholder="e.g., admin.dashboard" value="{{ old('route') }}" />
                            <small class="text-muted">Laravel route name for navigation</small>
                            @error('route')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="parent_id" class="form-label">Parent Module</label>
                            <x-select name="parent_id" placeholder="Select Parent (or leave as Root)">
                                @foreach($parentModules as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            @error('parent_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="order" class="form-label">Display Order</label>
                            <x-input type="number" name="order" placeholder="0" value="{{ old('order', 0) }}" />
                            @error('order')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <x-textarea name="description" placeholder="Enter module description" rows="3">{{ old('description') }}</x-textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <x-checkbox name="is_active" value="1" :checked="old('is_active', true)" label="Active" />
                        </div>
                        <div class="col-md-6">
                            <x-checkbox name="is_coming_soon" value="1" :checked="old('is_coming_soon', false)" label="Coming Soon" />
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <x-button type="submit" variant="gold" icon="bi bi-check-lg">Create Module</x-button>
                        <x-button href="{{ route('admin.modules.index') }}" variant="outline">Cancel</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
