{{-- 
    Modal Trigger Component - Button to trigger modal
    
    Usage:
    <x-modal-trigger target="myModal">Open Modal</x-modal-trigger>
    <x-modal-trigger target="formModal" variant="gold" icon="fas fa-plus">Add New</x-modal-trigger>
--}}

@props([
    'target' => null,
    'variant' => 'primary',
    'icon' => null,
])

@php
    $variantClass = match($variant) {
        'primary' => 'btn-primary-custom',
        'gold' => 'btn-gold',
        'outline' => 'btn-outline-custom',
        'outline-gold' => 'btn-outline-gold',
        'link' => 'btn-link',
        default => 'btn-' . $variant,
    };
@endphp

<button 
    type="button" 
    class="btn {{ $variantClass }}"
    data-bs-toggle="modal" 
    data-bs-target="#{{ $target }}"
    {{ $attributes }}
>
    @if($icon)
        <i class="{{ $icon }} me-1"></i>
    @endif
    {{ $slot }}
</button>
