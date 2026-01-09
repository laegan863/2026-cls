{{-- 
    Pricing Card Component - Pricing plan card
    
    Usage:
    <x-pricing-card 
        title="Pro Plan" 
        price="$49" 
        period="/month"
        featured
        :features="['Unlimited Projects', '50GB Storage', 'Priority Support']"
    />
--}}

@props([
    'title' => null,
    'price' => null,
    'period' => '/month',
    'description' => null,
    'features' => [],
    'buttonText' => 'Get Started',
    'buttonUrl' => '#',
    'featured' => false,
])

<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm h-100 position-relative overflow-hidden']) }} style="border-radius: var(--radius-lg); {{ $featured ? 'border: 2px solid var(--bs-gold);' : '' }}">
    @if($featured)
        <div class="position-absolute top-0 end-0 px-3 py-1" style="background: var(--bs-gold); color: var(--bs-primary-dark); font-size: 0.75rem; font-weight: 600; border-radius: 0 0 0 var(--radius);">
            Popular
        </div>
    @endif
    
    <div class="card-body text-center p-4">
        @if($title)
            <h5 class="mb-3" style="color: var(--bs-primary);">{{ $title }}</h5>
        @endif
        
        <div class="mb-3">
            <span style="font-size: 2.5rem; font-weight: 700; color: {{ $featured ? 'var(--bs-gold)' : 'var(--bs-primary)' }};">{{ $price }}</span>
            <span class="text-muted">{{ $period }}</span>
        </div>
        
        @if($description)
            <p class="text-muted small mb-4">{{ $description }}</p>
        @endif
        
        @if(count($features) > 0)
            <ul class="list-unstyled mb-4 text-start">
                @foreach($features as $feature)
                    <li class="py-2 d-flex align-items-center gap-2">
                        <i class="fas fa-check-circle" style="color: var(--bs-gold);"></i>
                        <span>{{ $feature }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
        
        {{ $slot }}
        
        <a href="{{ $buttonUrl }}" class="btn {{ $featured ? 'btn-gold' : 'btn-outline-custom' }} w-100">
            {{ $buttonText }}
        </a>
    </div>
</div>
