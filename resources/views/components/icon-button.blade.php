{{-- 
    Icon Button Component - Square icon-only buttons
    
    Usage:
    <x-icon-button icon="fas fa-plus" />
    <x-icon-button icon="fas fa-edit" variant="gold" />
    <x-icon-button icon="fas fa-trash" variant="danger" size="lg" />
--}}

@props([
    'icon' => null,
    'variant' => 'primary',
    'size' => null,
    'type' => 'button',
    'href' => null,
])

@php
    $variantStyles = match($variant) {
        'primary' => 'background: var(--bs-primary); color: white;',
        'gold' => 'background: var(--bs-gold); color: var(--bs-primary-dark);',
        'outline' => 'background: transparent; color: var(--bs-primary); border: 1px solid var(--bs-gray-300);',
        'success' => 'background: #10b981; color: white;',
        'danger' => 'background: #ef4444; color: white;',
        'warning' => 'background: #f59e0b; color: white;',
        'info' => 'background: #3b82f6; color: white;',
        'light' => 'background: var(--bs-gray-100); color: var(--bs-gray-700);',
        default => 'background: var(--bs-primary); color: white;',
    };
    
    $sizeStyles = match($size) {
        'sm' => 'width: 32px; height: 32px; font-size: 0.875rem;',
        'lg' => 'width: 48px; height: 48px; font-size: 1.25rem;',
        default => 'width: 40px; height: 40px;',
    };
@endphp

@if($href)
    <a 
        href="{{ $href }}" 
        {{ $attributes->merge(['class' => 'btn btn-icon']) }}
        style="{{ $variantStyles }} {{ $sizeStyles }} padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius); transition: all 0.2s;"
    >
        <i class="{{ $icon }}"></i>
    </a>
@else
    <button 
        type="{{ $type }}" 
        {{ $attributes->merge(['class' => 'btn btn-icon']) }}
        style="{{ $variantStyles }} {{ $sizeStyles }} padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius); transition: all 0.2s;"
    >
        <i class="{{ $icon }}"></i>
    </button>
@endif
