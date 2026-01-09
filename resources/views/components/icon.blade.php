{{-- 
    Icon Component - Icon wrapper with optional background
    
    Usage:
    <x-icon name="fas fa-star" />
    <x-icon name="bi bi-check" size="lg" variant="gold" />
    <x-icon name="fas fa-user" background />
--}}

@props([
    'name' => null,
    'size' => null,
    'variant' => 'primary',
    'background' => false,
])

@php
    $sizeStyles = match($size) {
        'xs' => 'font-size: 0.75rem;',
        'sm' => 'font-size: 1rem;',
        'lg' => 'font-size: 1.5rem;',
        'xl' => 'font-size: 2rem;',
        '2xl' => 'font-size: 2.5rem;',
        default => 'font-size: 1.25rem;',
    };
    
    $colorStyle = match($variant) {
        'primary' => 'color: var(--bs-primary);',
        'gold' => 'color: var(--bs-gold);',
        'success' => 'color: var(--bs-success);',
        'danger' => 'color: var(--bs-danger);',
        'warning' => 'color: var(--bs-warning);',
        'info' => 'color: var(--bs-info);',
        'white' => 'color: white;',
        'muted' => 'color: var(--bs-gray-500);',
        default => 'color: var(--bs-primary);',
    };
    
    $bgStyle = $background ? match($variant) {
        'primary' => 'background: rgba(26, 43, 74, 0.1); padding: 0.75rem; border-radius: var(--radius);',
        'gold' => 'background: rgba(212, 169, 76, 0.15); padding: 0.75rem; border-radius: var(--radius);',
        'success' => 'background: var(--bs-success-light); padding: 0.75rem; border-radius: var(--radius);',
        'danger' => 'background: var(--bs-danger-light); padding: 0.75rem; border-radius: var(--radius);',
        'warning' => 'background: var(--bs-warning-light); padding: 0.75rem; border-radius: var(--radius);',
        'info' => 'background: var(--bs-info-light); padding: 0.75rem; border-radius: var(--radius);',
        default => 'background: rgba(26, 43, 74, 0.1); padding: 0.75rem; border-radius: var(--radius);',
    } : '';
@endphp

@if($background)
    <div {{ $attributes->merge(['class' => 'd-inline-flex align-items-center justify-content-center']) }} style="{{ $bgStyle }}">
        <i class="{{ $name }}" style="{{ $sizeStyles }} {{ $colorStyle }}"></i>
    </div>
@else
    <i class="{{ $name }}" {{ $attributes }} style="{{ $sizeStyles }} {{ $colorStyle }}"></i>
@endif
