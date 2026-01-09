{{-- 
    Accordion Item Component - Individual accordion item
    
    Usage:
    <x-accordion-item id="one" title="Section Title" parent="myAccordion" show>
        Content here
    </x-accordion-item>
--}}

@props([
    'id' => 'accordion-item-' . uniqid(),
    'title' => null,
    'parent' => null,
    'show' => false,
    'icon' => null,
])

<div class="accordion-item" style="border: 1px solid var(--bs-gray-200); border-radius: var(--radius-lg); margin-bottom: 0.5rem; overflow: hidden;">
    <h2 class="accordion-header">
        <button 
            class="accordion-button {{ $show ? '' : 'collapsed' }}" 
            type="button" 
            data-bs-toggle="collapse" 
            data-bs-target="#{{ $id }}" 
            aria-expanded="{{ $show ? 'true' : 'false' }}" 
            aria-controls="{{ $id }}"
            style="background: var(--bs-white); color: var(--bs-primary); font-weight: 600;"
        >
            @if($icon)
                <i class="{{ $icon }} me-2"></i>
            @endif
            {{ $title }}
        </button>
    </h2>
    <div 
        id="{{ $id }}" 
        class="accordion-collapse collapse {{ $show ? 'show' : '' }}" 
        @if($parent) data-bs-parent="#{{ $parent }}" @endif
    >
        <div class="accordion-body">
            {{ $slot }}
        </div>
    </div>
</div>
