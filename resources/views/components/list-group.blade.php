{{-- 
    List Group Component - Bootstrap list group
    
    Usage:
    <x-list-group>
        <x-list-group-item>Item 1</x-list-group-item>
        <x-list-group-item active>Item 2 (active)</x-list-group-item>
        <x-list-group-item>Item 3</x-list-group-item>
    </x-list-group>
    
    <x-list-group flush>
        ...
    </x-list-group>
--}}

@props([
    'flush' => false,
    'numbered' => false,
])

@php
    $classes = 'list-group';
    if ($flush) $classes .= ' list-group-flush';
    if ($numbered) $classes .= ' list-group-numbered';
@endphp

<ul {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</ul>
