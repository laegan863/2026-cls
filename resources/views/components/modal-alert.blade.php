{{-- 
    Alert Modal Component - Pre-styled alert/confirmation modal
    
    Usage:
    <x-modal-alert id="successModal" type="success" title="Success!" message="Your action was completed." />
    <x-modal-alert id="deleteModal" type="danger" title="Delete Item?" message="Are you sure?" confirm="Delete" />
    <x-modal-alert id="warningModal" type="warning" title="Warning" message="This action is irreversible." />
--}}

@props([
    'id' => 'alert-modal-' . uniqid(),
    'type' => 'info',
    'title' => null,
    'message' => null,
    'confirm' => null,
    'cancel' => 'Close',
    'icon' => null,
])

@php
    $config = match($type) {
        'success' => [
            'icon' => $icon ?? 'fas fa-check-circle',
            'color' => '#10b981',
            'bg' => 'rgba(16, 185, 129, 0.1)',
        ],
        'danger' => [
            'icon' => $icon ?? 'fas fa-exclamation-triangle',
            'color' => '#ef4444',
            'bg' => 'rgba(239, 68, 68, 0.1)',
        ],
        'warning' => [
            'icon' => $icon ?? 'fas fa-exclamation-circle',
            'color' => '#f59e0b',
            'bg' => 'rgba(245, 158, 11, 0.1)',
        ],
        'info' => [
            'icon' => $icon ?? 'fas fa-info-circle',
            'color' => '#3b82f6',
            'bg' => 'rgba(59, 130, 246, 0.1)',
        ],
        default => [
            'icon' => $icon ?? 'fas fa-info-circle',
            'color' => 'var(--bs-primary)',
            'bg' => 'rgba(26, 43, 74, 0.1)',
        ],
    };
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true" {{ $attributes }}>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body py-5">
                <div class="mb-4">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: {{ $config['bg'] }}; display: inline-flex; align-items: center; justify-content: center;">
                        <i class="{{ $config['icon'] }}" style="font-size: 2.5rem; color: {{ $config['color'] }};"></i>
                    </div>
                </div>
                
                @if($title)
                    <h4 class="mb-2" style="color: var(--bs-primary);">{{ $title }}</h4>
                @endif
                
                @if($message)
                    <p class="text-muted mb-0">{{ $message }}</p>
                @endif
                
                {{ $slot }}
            </div>
            
            <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">{{ $cancel }}</button>
                @if($confirm)
                    <button type="button" class="btn btn-{{ $type === 'danger' ? 'danger' : ($type === 'success' ? 'success' : 'primary-custom') }}">
                        {{ $confirm }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
