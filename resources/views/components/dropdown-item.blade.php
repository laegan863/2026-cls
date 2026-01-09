{{-- 
    Dropdown Item Component - Individual dropdown menu item
    
    Usage:
    <x-dropdown-item href="/edit" icon="fas fa-edit">Edit</x-dropdown-item>
    <x-dropdown-item type="button" icon="fas fa-trash" class="text-danger">Delete</x-dropdown-item>
--}}

@props([
    'href' => null,
    'type' => null,
    'icon' => null,
])

<li>
    @if($type === 'button')
        <button {{ $attributes->merge(['class' => 'dropdown-item py-2', 'type' => 'button']) }}>
            @if($icon)
                <i class="{{ $icon }} me-2 text-muted"></i>
            @endif
            {{ $slot }}
        </button>
    @else
        <a href="{{ $href ?? '#' }}" {{ $attributes->merge(['class' => 'dropdown-item py-2']) }}>
            @if($icon)
                <i class="{{ $icon }} me-2 text-muted"></i>
            @endif
            {{ $slot }}
        </a>
    @endif
</li>
