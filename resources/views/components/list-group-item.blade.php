{{-- 
    List Group Item Component
    
    Usage:
    <x-list-group-item href="/link">Clickable item</x-list-group-item>
    <x-list-group-item active>Active item</x-list-group-item>
    <x-list-group-item disabled>Disabled item</x-list-group-item>
--}}

@props([
    'href' => null,
    'active' => false,
    'disabled' => false,
])

@php
    $classes = 'list-group-item list-group-item-action';
    if ($active) $classes .= ' active';
    if ($disabled) $classes .= ' disabled';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <li {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </li>
@endif
