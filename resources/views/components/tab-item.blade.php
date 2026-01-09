{{-- 
    Tab Item Component - Individual tab button
    
    Usage:
    <x-tab-item id="home" active>Home</x-tab-item>
    <x-tab-item id="profile">Profile</x-tab-item>
--}}

@props([
    'id' => null,
    'active' => false,
    'icon' => null,
])

<li class="nav-item" role="presentation">
    <button 
        class="nav-link {{ $active ? 'active' : '' }}" 
        id="{{ $id }}-tab" 
        data-bs-toggle="tab" 
        data-bs-target="#{{ $id }}" 
        type="button" 
        role="tab" 
        aria-controls="{{ $id }}" 
        aria-selected="{{ $active ? 'true' : 'false' }}"
        {{ $attributes }}
    >
        @if($icon)
            <i class="{{ $icon }} me-1"></i>
        @endif
        {{ $slot }}
    </button>
</li>
