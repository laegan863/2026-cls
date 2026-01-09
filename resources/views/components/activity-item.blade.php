{{-- 
    Activity Item Component - Timeline activity item
    
    Usage:
    <x-activity-item 
        icon="bi bi-cart-plus" 
        variant="blue" 
        title="New order received" 
        time="5 minutes ago"
    />
--}}

@props([
    'icon' => 'bi bi-circle-fill',
    'variant' => 'blue',
    'title' => null,
    'time' => null,
])

<div {{ $attributes->merge(['class' => 'activity-item']) }}>
    <div class="activity-icon {{ $variant }}">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="activity-content">
        @if($title)
            <div class="activity-title">{{ $title }}</div>
        @endif
        @if($time)
            <div class="activity-time">{{ $time }}</div>
        @endif
        {{ $slot }}
    </div>
</div>
