{{-- 
    Breadcrumb Item Component - Individual breadcrumb item
    
    Usage:
    <x-breadcrumb-item href="/admin">Dashboard</x-breadcrumb-item>
    <x-breadcrumb-item active>Current Page</x-breadcrumb-item>
--}}

@props([
    'href' => null,
    'active' => false,
])

@if($active)
    <span {{ $attributes }}>{{ $slot }}</span>
@else
    <a href="{{ $href ?? '#' }}" {{ $attributes }}>{{ $slot }}</a>
    <span class="separator"><i class="bi bi-chevron-right"></i></span>
@endif
