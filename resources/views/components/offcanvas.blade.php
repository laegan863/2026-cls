{{-- 
    Offcanvas Component - Slide-out panel
    
    Usage:
    <x-offcanvas id="sidePanel" title="Settings" placement="end">
        Panel content here
    </x-offcanvas>
    
    Trigger with:
    <button data-bs-toggle="offcanvas" data-bs-target="#sidePanel">Open</button>
--}}

@props([
    'id' => 'offcanvas-' . uniqid(),
    'title' => null,
    'placement' => 'start',
    'backdrop' => true,
    'scroll' => false,
])

<div 
    class="offcanvas offcanvas-{{ $placement }}" 
    id="{{ $id }}" 
    tabindex="-1" 
    aria-labelledby="{{ $id }}Label"
    @unless($backdrop) data-bs-backdrop="false" @endunless
    @if($scroll) data-bs-scroll="true" @endif
    {{ $attributes }}
>
    <div class="offcanvas-header" style="background: var(--bs-primary); color: white;">
        @if($title)
            <h5 class="offcanvas-title" id="{{ $id }}Label">{{ $title }}</h5>
        @endif
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        {{ $slot }}
    </div>
</div>
