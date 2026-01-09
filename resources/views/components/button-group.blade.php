{{-- 
    Button Group Component - Group of buttons
    
    Usage:
    <x-button-group>
        <x-button variant="outline">Left</x-button>
        <x-button variant="outline">Middle</x-button>
        <x-button variant="outline">Right</x-button>
    </x-button-group>
    
    <x-button-group size="sm" vertical>
        ...
    </x-button-group>
--}}

@props([
    'size' => null,
    'vertical' => false,
])

@php
    $classes = $vertical ? 'btn-group-vertical' : 'btn-group';
    if ($size === 'sm') $classes .= ' btn-group-sm';
    if ($size === 'lg') $classes .= ' btn-group-lg';
@endphp

<div {{ $attributes->merge(['class' => $classes, 'role' => 'group']) }}>
    {{ $slot }}
</div>
