{{-- 
    Modal Component - Bootstrap modal wrapper
    
    Usage:
    <x-modal id="myModal" title="Modal Title">
        <p>Modal content here</p>
    </x-modal>
    
    <x-modal id="formModal" title="Contact Form" size="lg">
        <x-slot:footer>
            <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary-custom">Submit</button>
        </x-slot:footer>
        
        <form>...</form>
    </x-modal>
    
    Sizes: sm, default, lg, xl, fullscreen
    Variants: default, centered, scrollable
--}}

@props([
    'id' => 'modal-' . uniqid(),
    'title' => null,
    'size' => null,
    'centered' => false,
    'scrollable' => false,
    'static' => false,
    'hideClose' => false,
])

@php
    $sizeClass = match($size) {
        'sm' => 'modal-sm',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        'fullscreen' => 'modal-fullscreen',
        default => '',
    };
    
    $dialogClass = 'modal-dialog ' . $sizeClass;
    if ($centered) $dialogClass .= ' modal-dialog-centered';
    if ($scrollable) $dialogClass .= ' modal-dialog-scrollable';
@endphp

<div 
    class="modal fade" 
    id="{{ $id }}" 
    tabindex="-1" 
    aria-labelledby="{{ $id }}Label" 
    aria-hidden="true"
    @if($static) data-bs-backdrop="static" data-bs-keyboard="false" @endif
    {{ $attributes }}
>
    <div class="{{ $dialogClass }}">
        <div class="modal-content">
            @if($title || !$hideClose)
                <div class="modal-header">
                    @if($title)
                        <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                    @endif
                    @unless($hideClose)
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    @endunless
                </div>
            @endif
            
            <div class="modal-body">
                {{ $slot }}
            </div>
            
            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
