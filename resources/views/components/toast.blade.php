{{-- 
    Toast Component - Toast notification
    
    Usage:
    <x-toast id="successToast" title="Success" variant="success">
        Your changes have been saved.
    </x-toast>
    
    Trigger with:
    new bootstrap.Toast(document.getElementById('successToast')).show();
--}}

@props([
    'id' => 'toast-' . uniqid(),
    'title' => null,
    'time' => 'Just now',
    'variant' => null,
    'autohide' => true,
    'delay' => 5000,
])

@php
    $headerBg = match($variant) {
        'success' => 'background: var(--bs-success-light); color: #065f46;',
        'danger' => 'background: var(--bs-danger-light); color: #991b1b;',
        'warning' => 'background: var(--bs-warning-light); color: #92400e;',
        'info' => 'background: var(--bs-info-light); color: #1e40af;',
        'primary' => 'background: rgba(26, 43, 74, 0.1); color: var(--bs-primary);',
        'gold' => 'background: rgba(212, 169, 76, 0.15); color: var(--bs-gold-dark);',
        default => '',
    };
@endphp

<div 
    class="toast" 
    id="{{ $id }}" 
    role="alert" 
    aria-live="assertive" 
    aria-atomic="true"
    data-bs-autohide="{{ $autohide ? 'true' : 'false' }}"
    data-bs-delay="{{ $delay }}"
    style="border-radius: var(--radius-lg); border: none; box-shadow: var(--shadow-lg);"
    {{ $attributes }}
>
    @if($title)
        <div class="toast-header" style="{{ $headerBg }} border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
            <strong class="me-auto">{{ $title }}</strong>
            <small>{{ $time }}</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    @endif
    <div class="toast-body">
        {{ $slot }}
        @unless($title)
            <button type="button" class="btn-close float-end" data-bs-dismiss="toast" aria-label="Close"></button>
        @endunless
    </div>
</div>
