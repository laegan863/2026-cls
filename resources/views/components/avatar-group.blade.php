{{-- 
    Avatar Group Component - Stack of avatars
    
    Usage:
    <x-avatar-group>
        <x-avatar name="John Doe" />
        <x-avatar name="Jane Smith" />
        <x-avatar name="Bob Wilson" />
    </x-avatar-group>
    
    <x-avatar-group :max="3" :total="8">
        <x-avatar name="A" />
        <x-avatar name="B" />
        <x-avatar name="C" />
    </x-avatar-group>
--}}

@props([
    'max' => null,
    'total' => null,
])

<div {{ $attributes->merge(['class' => 'avatar-group d-inline-flex']) }}>
    {{ $slot }}
    
    @if($max && $total && $total > $max)
        <div class="avatar more" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; background: var(--bs-gray-200); color: var(--bs-gray-700); border-radius: 50%; font-weight: 600; margin-left: -10px; border: 2px solid white;">
            +{{ $total - $max }}
        </div>
    @endif
</div>
