{{-- 
    Alert Component - Dismissible alert messages
    
    Usage:
    <x-alert type="success">Your changes have been saved!</x-alert>
    <x-alert type="danger" title="Error!">Something went wrong.</x-alert>
    <x-alert type="info" icon="fas fa-info-circle" :dismissible="false">This is an info message.</x-alert>
--}}

@props([
    'type' => 'info',
    'title' => null,
    'icon' => null,
    'dismissible' => true,
])

@php
    $config = match($type) {
        'success' => [
            'class' => 'alert-success',
            'icon' => $icon ?? 'fas fa-check-circle',
            'bg' => 'var(--bs-success-light)',
            'border' => 'var(--bs-success)',
            'color' => '#065f46',
        ],
        'danger', 'error' => [
            'class' => 'alert-danger',
            'icon' => $icon ?? 'fas fa-exclamation-circle',
            'bg' => 'var(--bs-danger-light)',
            'border' => 'var(--bs-danger)',
            'color' => '#991b1b',
        ],
        'warning' => [
            'class' => 'alert-warning',
            'icon' => $icon ?? 'fas fa-exclamation-triangle',
            'bg' => 'var(--bs-warning-light)',
            'border' => 'var(--bs-warning)',
            'color' => '#92400e',
        ],
        'info' => [
            'class' => 'alert-info',
            'icon' => $icon ?? 'fas fa-info-circle',
            'bg' => 'var(--bs-info-light)',
            'border' => 'var(--bs-info)',
            'color' => '#1e40af',
        ],
        'primary' => [
            'class' => 'alert-primary',
            'icon' => $icon ?? 'fas fa-bell',
            'bg' => 'rgba(26, 43, 74, 0.1)',
            'border' => 'var(--bs-primary)',
            'color' => 'var(--bs-primary)',
        ],
        'gold' => [
            'class' => 'alert-warning',
            'icon' => $icon ?? 'fas fa-star',
            'bg' => 'rgba(212, 169, 76, 0.15)',
            'border' => 'var(--bs-gold)',
            'color' => 'var(--bs-gold-dark)',
        ],
        default => [
            'class' => 'alert-secondary',
            'icon' => $icon ?? 'fas fa-info-circle',
            'bg' => 'var(--bs-gray-100)',
            'border' => 'var(--bs-gray-300)',
            'color' => 'var(--bs-gray-700)',
        ],
    };
@endphp

<div 
    {{ $attributes->merge(['class' => 'alert d-flex align-items-start gap-3 ' . ($dismissible ? 'alert-dismissible fade show' : '')]) }}
    style="background: {{ $config['bg'] }}; border: 1px solid {{ $config['border'] }}; border-radius: var(--radius-lg); color: {{ $config['color'] }};"
    role="alert"
>
    <i class="{{ $config['icon'] }}" style="font-size: 1.25rem; margin-top: 0.125rem;"></i>
    
    <div class="flex-grow-1">
        @if($title)
            <strong class="d-block mb-1">{{ $title }}</strong>
        @endif
        {{ $slot }}
    </div>
    
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: relative; top: 0; right: 0; padding: 0.75rem;"></button>
    @endif
</div>
