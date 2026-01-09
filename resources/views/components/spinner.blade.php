{{-- 
    Spinner Component - Loading spinner
    
    Usage:
    <x-spinner />
    <x-spinner size="sm" />
    <x-spinner variant="gold" />
    <x-spinner type="grow" />
--}}

@props([
    'size' => null,
    'variant' => 'primary',
    'type' => 'border',
])

@php
    $sizeClass = match($size) {
        'sm' => 'spinner-' . $type . '-sm',
        default => '',
    };
    
    $colorStyle = match($variant) {
        'primary' => 'color: var(--bs-primary);',
        'gold' => 'color: var(--bs-gold);',
        'white' => 'color: white;',
        default => 'color: var(--bs-primary);',
    };
@endphp

<div 
    {{ $attributes->merge(['class' => 'spinner-' . $type . ' ' . $sizeClass, 'role' => 'status']) }}
    style="{{ $colorStyle }}"
>
    <span class="visually-hidden">Loading...</span>
</div>
