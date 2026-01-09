{{-- 
    Image Card Component - Card with image header
    
    Usage:
    <x-image-card 
        image="https://example.com/image.jpg" 
        title="Card Title"
        subtitle="Subtitle text"
    >
        <p>Card content</p>
        
        <x-slot:footer>
            <a href="#">Read More</a>
        </x-slot:footer>
    </x-image-card>
--}}

@props([
    'image' => null,
    'title' => null,
    'subtitle' => null,
    'imageHeight' => '200px',
    'overlay' => false,
])

<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm overflow-hidden']) }} style="border-radius: var(--radius-lg);">
    @if($image)
        <div class="position-relative" style="height: {{ $imageHeight }}; overflow: hidden;">
            <img src="{{ $image }}" alt="{{ $title }}" class="w-100 h-100" style="object-fit: cover;">
            @if($overlay)
                <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                    @if($title)
                        <h5 class="text-white mb-0">{{ $title }}</h5>
                    @endif
                    @if($subtitle)
                        <small class="text-white-50">{{ $subtitle }}</small>
                    @endif
                </div>
            @endif
        </div>
    @endif
    
    <div class="card-body">
        @if($title && !$overlay)
            <h5 class="card-title" style="color: var(--bs-primary);">{{ $title }}</h5>
        @endif
        @if($subtitle && !$overlay)
            <p class="card-text text-muted small">{{ $subtitle }}</p>
        @endif
        
        {{ $slot }}
    </div>
    
    @isset($footer)
        <div class="card-footer bg-transparent border-top">
            {{ $footer }}
        </div>
    @endisset
</div>
