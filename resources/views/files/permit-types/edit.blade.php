@extends('layouts.index')

@section('title', 'Edit Permit Type')

@section('content')
    <x-page-header title="Edit Permit Type" subtitle="Update permit type information.">
        <x-button href="{{ route('admin.permit-types.index') }}" variant="outline" icon="bi bi-arrow-left">Back to Permit Types</x-button>
    </x-page-header>

    <div class="row">
        <div class="col-lg-8">
            <x-card title="Permit Type Information" icon="bi bi-file-earmark-check">
                <form action="{{ route('admin.permit-types.update', $permitType) }}" method="POST" id="permitTypeForm">
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
                        <label class="form-label">Sub Types <small class="text-muted">(Optional - Add as many as needed)</small></label>
                        <div id="subTypesContainer">
                            @php
                                $subTypes = old('sub_type', $permitType->sub_type ?? []);
                            @endphp
                            @if($subTypes && count($subTypes) > 0)
                                @foreach($subTypes as $index => $subType)
                                    <div class="input-group mb-2 sub-type-row">
                                        <input type="text" name="sub_type[]" class="form-control" placeholder="Enter sub type" value="{{ $subType }}">
                                        <button type="button" class="btn btn-outline-danger remove-sub-type" title="Remove">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2 sub-type-row">
                                    <input type="text" name="sub_type[]" class="form-control" placeholder="Enter sub type">
                                    <button type="button" class="btn btn-outline-danger remove-sub-type" title="Remove">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addSubType">
                            <i class="bi bi-plus-lg me-1"></i>Add Sub Type
                        </button>
                        @error('sub_type.*')
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('subTypesContainer');
        const addBtn = document.getElementById('addSubType');

        // Add new sub type row
        addBtn.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'input-group mb-2 sub-type-row';
            row.innerHTML = `
                <input type="text" name="sub_type[]" class="form-control" placeholder="Enter sub type">
                <button type="button" class="btn btn-outline-danger remove-sub-type" title="Remove">
                    <i class="bi bi-x-lg"></i>
                </button>
            `;
            container.appendChild(row);
        });

        // Remove sub type row
        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-sub-type')) {
                const rows = container.querySelectorAll('.sub-type-row');
                if (rows.length > 1) {
                    e.target.closest('.sub-type-row').remove();
                } else {
                    // Clear the input if it's the last row
                    rows[0].querySelector('input').value = '';
                }
            }
        });
    });
</script>
@endpush
