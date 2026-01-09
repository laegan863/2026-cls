{{-- 
    Tabs Component - Tab navigation
    
    Usage:
    <x-tabs active="profile">
        <x-slot:nav>
            <x-tab-item id="home">Home</x-tab-item>
            <x-tab-item id="profile">Profile</x-tab-item>
            <x-tab-item id="settings">Settings</x-tab-item>
        </x-slot:nav>
        
        <x-tab-panel id="home">Home content</x-tab-panel>
        <x-tab-panel id="profile">Profile content</x-tab-panel>
        <x-tab-panel id="settings">Settings content</x-tab-panel>
    </x-tabs>
--}}

@props([
    'active' => null,
    'pills' => false,
    'vertical' => false,
])

<div {{ $attributes->merge(['class' => $vertical ? 'd-flex' : '']) }}>
    <ul class="nav {{ $pills ? 'nav-pills' : 'nav-tabs' }} {{ $vertical ? 'flex-column me-4' : 'mb-3' }}" role="tablist">
        {{ $nav }}
    </ul>
    
    <div class="tab-content {{ $vertical ? 'flex-grow-1' : '' }}">
        {{ $slot }}
    </div>
</div>
