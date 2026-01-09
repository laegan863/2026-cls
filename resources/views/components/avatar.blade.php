{{-- 
    Avatar Component - User avatar with fallback
    
    Usage:
    <x-avatar name="John Doe" />
    <x-avatar name="Jane Smith" image="https://example.com/avatar.jpg" />
    <x-avatar name="AB" size="lg" variant="gold" />
    <x-avatar name="User" size="sm" status="online" />
--}}

@props([
    'name' => 'U',
    'image' => null,
    'size' => null,
    'variant' => 'primary',
    'status' => null,
])

@php
    $initials = collect(explode(' ', $name))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->join('');
    
    $sizeStyles = match($size) {
        'xs' => 'width: 24px; height: 24px; font-size: 0.625rem;',
        'sm' => 'width: 32px; height: 32px; font-size: 0.75rem;',
        'lg' => 'width: 56px; height: 56px; font-size: 1.25rem;',
        'xl' => 'width: 80px; height: 80px; font-size: 1.75rem;',
        default => 'width: 40px; height: 40px; font-size: 0.875rem;',
    };
    
    $variantStyles = match($variant) {
        'primary' => 'background: var(--bs-primary); color: white;',
        'gold' => 'background: var(--bs-gold); color: var(--bs-primary-dark);',
        'light' => 'background: var(--bs-gray-200); color: var(--bs-gray-700);',
        default => 'background: var(--bs-primary); color: white;',
    };
    
    $statusColor = match($status) {
        'online' => '#10b981',
        'offline' => '#94a3b8',
        'busy' => '#ef4444',
        'away' => '#f59e0b',
        default => null,
    };
@endphp

<div {{ $attributes->merge(['class' => 'avatar position-relative d-inline-flex']) }} style="{{ $sizeStyles }}">
    @if($image)
        <img 
            src="{{ $image }}" 
            alt="{{ $name }}" 
            class="rounded-circle w-100 h-100" 
            style="object-fit: cover;"
        />
    @else
        <div 
            class="rounded-circle w-100 h-100 d-flex align-items-center justify-content-center fw-semibold" 
            style="{{ $variantStyles }}"
        >
            {{ $initials }}
        </div>
    @endif
    
    @if($status)
        <span 
            class="position-absolute rounded-circle" 
            style="width: 25%; height: 25%; min-width: 8px; min-height: 8px; bottom: 0; right: 0; background: {{ $statusColor }}; border: 2px solid white;"
        ></span>
    @endif
</div>
