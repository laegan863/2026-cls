{{-- 
    Status Badge Component - For showing status labels
    
    Usage:
    <x-status status="active" />
    <x-status status="pending" />
    <x-status status="completed" />
    <x-status status="cancelled" />
--}}

@props([
    'status' => null,
])

@php
    $config = match(strtolower($status)) {
        'active', 'completed', 'success', 'paid', 'approved', 'published' => [
            'class' => 'success',
            'label' => ucfirst($status),
        ],
        'pending', 'processing', 'in progress', 'review' => [
            'class' => 'warning',
            'label' => ucfirst($status),
        ],
        'inactive', 'cancelled', 'failed', 'rejected', 'deleted' => [
            'class' => 'danger',
            'label' => ucfirst($status),
        ],
        'draft', 'new', 'info' => [
            'class' => 'info',
            'label' => ucfirst($status),
        ],
        default => [
            'class' => 'secondary',
            'label' => ucfirst($status ?? 'Unknown'),
        ],
    };
@endphp

<span {{ $attributes->merge(['class' => 'status-badge ' . $config['class']]) }}>
    {{ $slot->isEmpty() ? $config['label'] : $slot }}
</span>
