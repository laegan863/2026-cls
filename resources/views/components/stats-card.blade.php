{{-- 
    Stats Card Component - Dashboard statistics card
    
    Usage:
    <x-stats-card 
        title="Total Revenue" 
        value="$48,592" 
        icon="bi bi-currency-dollar"
        change="+12.5%"
        trend="up"
    />
    
    <x-stats-card 
        title="Conversion Rate" 
        value="94.2%" 
        variant="gold"
        change="-2.4%"
        trend="down"
    />
--}}

@props([
    'title' => null,
    'value' => null,
    'icon' => null,
    'change' => null,
    'trend' => null,
    'variant' => 'default',
])

@php
    $cardClass = 'stats-card';
    if ($variant === 'primary') $cardClass .= ' primary';
    if ($variant === 'gold') $cardClass .= ' gold';
@endphp

<div {{ $attributes->merge(['class' => $cardClass]) }}>
    <div class="stats-card-header">
        @if($icon)
            <div class="stats-icon {{ $variant === 'gold' ? '' : ($variant === 'primary' ? '' : 'gold') }}">
                <i class="{{ $icon }}"></i>
            </div>
        @endif
    </div>
    
    <div class="stats-value">{{ $value }}</div>
    <div class="stats-label">{{ $title }}</div>
    
    @if($change)
        <div class="stats-change {{ $trend === 'up' ? 'positive' : ($trend === 'down' ? 'negative' : '') }}">
            @if($trend === 'up')
                <i class="bi bi-arrow-up"></i>
            @elseif($trend === 'down')
                <i class="bi bi-arrow-down"></i>
            @endif
            {{ $change }} from last month
        </div>
    @endif
    
    {{ $slot }}
</div>
