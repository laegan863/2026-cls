{{-- 
    Card Component - Content card wrapper
    
    Usage:
    <x-card title="Card Title">
        <p>Card content here</p>
    </x-card>
    
    <x-card title="With Actions">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-custom">View All</button>
        </x-slot:actions>
        
        Content here
    </x-card>
    
    <x-card title="With Footer">
        <x-slot:footer>
            <a href="#">View Details</a>
        </x-slot:footer>
        
        Content here
    </x-card>
--}}

@props([
    'title' => null,
    'icon' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'content-card']) }}>
    @if($title || isset($actions))
        <div class="content-card-header">
            <h3 class="content-card-title">
                @if($icon)
                    <i class="{{ $icon }} me-2"></i>
                @endif
                {{ $title }}
            </h3>
            @isset($actions)
                <div class="content-card-actions">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endif
    
    <div class="{{ $padding ? 'content-card-body' : '' }}">
        {{ $slot }}
    </div>
    
    @isset($footer)
        <div class="content-card-footer">
            {{ $footer }}
        </div>
    @endisset
</div>
