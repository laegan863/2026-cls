{{-- 
    Button Component - Styled buttons
    
    Usage:
    <x-button>Default Button</x-button>
    <x-button variant="gold">Gold Button</x-button>
    <x-button variant="outline" icon="fas fa-plus">Add New</x-button>
    <x-button variant="primary" size="lg" :loading="true">Processing...</x-button>
    <x-button href="/dashboard">Link Button</x-button>
--}}

@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => null,
    'icon' => null,
    'iconRight' => null,
    'loading' => false,
    'disabled' => false,
    'href' => null,
    'block' => false,
])

@php
    $variantClass = match($variant) {
        'primary' => 'btn-primary-custom',
        'gold' => 'btn-gold',
        'outline' => 'btn-outline-custom',
        'outline-gold' => 'btn-outline-gold',
        'success' => 'btn-success',
        'danger' => 'btn-danger',
        'warning' => 'btn-warning',
        'info' => 'btn-info',
        'light' => 'btn-light',
        'dark' => 'btn-dark',
        'link' => 'btn-link',
        default => 'btn-' . $variant,
    };
    
    $sizeClass = match($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        default => '',
    };
    
    $classes = 'btn ' . $variantClass . ' ' . $sizeClass;
    if ($block) $classes .= ' w-100';
    if ($loading) $classes .= ' disabled';
@endphp

@if($href)
    <a 
        href="{{ $href }}" 
        {{ $attributes->merge(['class' => $classes]) }}
        @if($disabled) aria-disabled="true" tabindex="-1" @endif
    >
        @if($loading)
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        @elseif($icon)
            <i class="{{ $icon }} me-1"></i>
        @endif
        {{ $slot }}
        @if($iconRight)
            <i class="{{ $iconRight }} ms-1"></i>
        @endif
    </a>
@else
    <button 
        type="{{ $type }}" 
        {{ $attributes->merge(['class' => $classes]) }}
        @if($disabled || $loading) disabled @endif
    >
        @if($loading)
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        @elseif($icon)
            <i class="{{ $icon }} me-1"></i>
        @endif
        {{ $slot }}
        @if($iconRight)
            <i class="{{ $iconRight }} ms-1"></i>
        @endif
    </button>
@endif
