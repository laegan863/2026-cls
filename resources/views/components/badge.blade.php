{{-- 
    Badge Component - Styled badges/labels
    
    Usage:
    <x-badge>Default</x-badge>
    <x-badge type="success">Active</x-badge>
    <x-badge type="danger" pill>Deleted</x-badge>
    <x-badge type="gold" icon="fas fa-star">Featured</x-badge>
--}}

@props([
    'type' => 'primary',
    'pill' => false,
    'icon' => null,
    'size' => null,
])

@php
    $styles = match($type) {
        'primary' => 'background: var(--bs-primary); color: white;',
        'gold' => 'background: var(--bs-gold); color: var(--bs-primary-dark);',
        'success' => 'background: var(--bs-success-light); color: #065f46;',
        'danger' => 'background: var(--bs-danger-light); color: #991b1b;',
        'warning' => 'background: var(--bs-warning-light); color: #92400e;',
        'info' => 'background: var(--bs-info-light); color: #1e40af;',
        'light' => 'background: var(--bs-gray-200); color: var(--bs-gray-700);',
        'dark' => 'background: var(--bs-gray-800); color: white;',
        default => 'background: var(--bs-gray-200); color: var(--bs-gray-700);',
    };
    
    $sizeStyles = match($size) {
        'sm' => 'font-size: 0.625rem; padding: 0.2rem 0.4rem;',
        'lg' => 'font-size: 0.875rem; padding: 0.4rem 0.75rem;',
        default => 'font-size: 0.75rem; padding: 0.3rem 0.6rem;',
    };
@endphp

<span 
    {{ $attributes->merge(['class' => 'badge']) }}
    style="{{ $styles }} {{ $sizeStyles }} border-radius: {{ $pill ? '9999px' : 'var(--radius)' }}; font-weight: 600;"
>
    @if($icon)
        <i class="{{ $icon }} me-1"></i>
    @endif
    {{ $slot }}
</span>
