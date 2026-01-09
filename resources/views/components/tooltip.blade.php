{{-- 
    Tooltip Component - Tooltip wrapper
    
    Usage:
    <x-tooltip text="This is a tooltip">
        <button class="btn btn-primary-custom">Hover me</button>
    </x-tooltip>
    
    <x-tooltip text="Top tooltip" placement="top">
        <span>Hover</span>
    </x-tooltip>
--}}

@props([
    'text' => '',
    'placement' => 'top',
])

<span 
    data-bs-toggle="tooltip" 
    data-bs-placement="{{ $placement }}" 
    title="{{ $text }}"
    {{ $attributes }}
>
    {{ $slot }}
</span>

@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endonce
