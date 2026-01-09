{{-- 
    Tab Panel Component - Tab content panel
    
    Usage:
    <x-tab-panel id="home" active>Home content here</x-tab-panel>
    <x-tab-panel id="profile">Profile content here</x-tab-panel>
--}}

@props([
    'id' => null,
    'active' => false,
])

<div 
    class="tab-pane fade {{ $active ? 'show active' : '' }}" 
    id="{{ $id }}" 
    role="tabpanel" 
    aria-labelledby="{{ $id }}-tab"
    {{ $attributes }}
>
    {{ $slot }}
</div>
