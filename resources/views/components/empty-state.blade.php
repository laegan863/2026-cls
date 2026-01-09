{{-- 
    Empty State Component - For empty data states
    
    Usage:
    <x-empty-state 
        title="No orders yet" 
        message="When you receive orders, they will appear here."
        icon="bi bi-inbox"
    />
    
    <x-empty-state title="No results found">
        <x-button variant="gold" icon="fas fa-plus">Create New</x-button>
    </x-empty-state>
--}}

@props([
    'title' => 'No data found',
    'message' => null,
    'icon' => 'bi bi-inbox',
    'image' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-5']) }}>
    @if($image)
        <img src="{{ $image }}" alt="" class="mb-4" style="max-width: 200px; opacity: 0.7;">
    @else
        <div class="mb-4">
            <div style="width: 100px; height: 100px; margin: 0 auto; background: var(--bs-gray-100); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="{{ $icon }}" style="font-size: 2.5rem; color: var(--bs-gray-400);"></i>
            </div>
        </div>
    @endif
    
    <h5 style="color: var(--bs-primary);">{{ $title }}</h5>
    
    @if($message)
        <p class="text-muted mb-4">{{ $message }}</p>
    @endif
    
    {{ $slot }}
</div>
