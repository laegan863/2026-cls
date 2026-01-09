{{-- 
    Progress Component - Progress bar
    
    Usage:
    <x-progress value="75" />
    <x-progress value="45" variant="gold" />
    <x-progress value="90" label show-value />
    <x-progress value="60" striped animated />
--}}

@props([
    'value' => 0,
    'max' => 100,
    'variant' => 'primary',
    'label' => null,
    'showValue' => false,
    'height' => null,
    'striped' => false,
    'animated' => false,
])

@php
    $percentage = ($value / $max) * 100;
    $barClass = 'progress-bar';
    if ($striped) $barClass .= ' progress-bar-striped';
    if ($animated) $barClass .= ' progress-bar-animated';
@endphp

<div {{ $attributes }}>
    @if($label || $showValue)
        <div class="d-flex justify-content-between mb-1">
            @if($label)
                <span class="small fw-medium">{{ $label }}</span>
            @endif
            @if($showValue)
                <span class="small text-muted">{{ round($percentage) }}%</span>
            @endif
        </div>
    @endif
    
    <div class="progress-custom" @if($height) style="height: {{ $height }};" @endif>
        <div 
            class="{{ $barClass }} {{ $variant === 'gold' ? 'gold' : '' }}" 
            role="progressbar" 
            style="width: {{ $percentage }}%;" 
            aria-valuenow="{{ $value }}" 
            aria-valuemin="0" 
            aria-valuemax="{{ $max }}"
        ></div>
    </div>
</div>
