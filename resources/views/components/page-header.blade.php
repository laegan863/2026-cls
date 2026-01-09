{{-- 
    Page Header Component - Page title and actions
    
    Usage:
    <x-page-header title="Dashboard" subtitle="Welcome back, John!">
        <x-button variant="gold" icon="bi bi-plus-lg">New Report</x-button>
    </x-page-header>
--}}

@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'page-header d-flex justify-content-between align-items-center flex-wrap gap-3']) }}>
    <div>
        @if($title)
            <h1 class="page-title">{{ $title }}</h1>
        @endif
        @if($subtitle)
            <p class="page-subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    
    @if(!$slot->isEmpty())
        <div class="d-flex gap-2">
            {{ $slot }}
        </div>
    @endif
</div>
