{{-- 
    Dropdown Component - Dropdown menu wrapper
    
    Usage:
    <x-dropdown>
        <x-slot:trigger>
            <x-button variant="outline">Options <i class="bi bi-chevron-down ms-1"></i></x-button>
        </x-slot:trigger>
        
        <x-dropdown-item href="/edit" icon="fas fa-edit">Edit</x-dropdown-item>
        <x-dropdown-item href="/delete" icon="fas fa-trash" class="text-danger">Delete</x-dropdown-item>
        <x-dropdown-divider />
        <x-dropdown-item href="/archive">Archive</x-dropdown-item>
    </x-dropdown>
--}}

@props([
    'align' => 'start',
])

<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <div data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
        {{ $trigger }}
    </div>
    
    <ul class="dropdown-menu dropdown-menu-{{ $align }} shadow-lg" style="border: none; border-radius: var(--radius-lg); padding: 0.5rem;">
        {{ $slot }}
    </ul>
</div>
