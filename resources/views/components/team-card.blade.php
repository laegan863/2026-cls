{{-- 
    Team Card Component - Team member profile card
    
    Usage:
    <x-team-card 
        name="John Doe" 
        role="CEO & Founder"
        image="https://example.com/avatar.jpg"
        :socials="['twitter' => '#', 'linkedin' => '#', 'github' => '#']"
    />
--}}

@props([
    'name' => null,
    'role' => null,
    'image' => null,
    'bio' => null,
    'socials' => [],
])

<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm text-center h-100']) }} style="border-radius: var(--radius-lg);">
    <div class="card-body p-4">
        @if($image)
            <div class="mb-3">
                <img src="{{ $image }}" alt="{{ $name }}" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid var(--bs-gold);">
            </div>
        @else
            <div class="mb-3 mx-auto" style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid var(--bs-gold);">
                <span style="font-size: 2rem; color: var(--bs-white); font-weight: 600;">{{ substr($name, 0, 2) }}</span>
            </div>
        @endif
        
        @if($name)
            <h5 class="mb-1" style="color: var(--bs-primary);">{{ $name }}</h5>
        @endif
        
        @if($role)
            <p class="text-muted small mb-3">{{ $role }}</p>
        @endif
        
        @if($bio)
            <p class="small text-muted mb-3">{{ $bio }}</p>
        @endif
        
        {{ $slot }}
        
        @if(count($socials) > 0)
            <div class="d-flex justify-content-center gap-2 mt-3">
                @foreach($socials as $platform => $url)
                    <a href="{{ $url }}" class="btn btn-sm" style="width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; background: var(--bs-gray-100); color: var(--bs-primary); border-radius: 50%; transition: all 0.2s;">
                        <i class="fab fa-{{ $platform }}"></i>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
