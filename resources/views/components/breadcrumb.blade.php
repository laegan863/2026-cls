{{-- 
    Breadcrumb Component - Navigation breadcrumb
    
    Usage:
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/admin'],
        ['label' => 'Users', 'url' => '/admin/users'],
        ['label' => 'Edit User'],
    ]" />
    
    Or with slot:
    <x-breadcrumb>
        <x-breadcrumb-item href="/admin">Dashboard</x-breadcrumb-item>
        <x-breadcrumb-item href="/admin/users">Users</x-breadcrumb-item>
        <x-breadcrumb-item active>Edit User</x-breadcrumb-item>
    </x-breadcrumb>
--}}

@props([
    'items' => [],
])

<nav {{ $attributes->merge(['class' => 'breadcrumb-custom']) }}>
    @if(count($items) > 0)
        @foreach($items as $index => $item)
            @if($index < count($items) - 1)
                <a href="{{ $item['url'] ?? '#' }}">{{ $item['label'] }}</a>
                <span class="separator"><i class="bi bi-chevron-right"></i></span>
            @else
                <span>{{ $item['label'] }}</span>
            @endif
        @endforeach
    @else
        {{ $slot }}
    @endif
</nav>
