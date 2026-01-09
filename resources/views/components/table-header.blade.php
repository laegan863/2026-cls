{{-- 
    Table Header Component - Sortable table header
    
    Usage:
    <x-table-header column="name" :sort="$sort" :direction="$direction">Name</x-table-header>
    <x-table-header column="email" :sort="$sort" :direction="$direction">Email</x-table-header>
--}}

@props([
    'column' => null,
    'sort' => null,
    'direction' => 'asc',
    'sortable' => true,
])

@php
    $isActive = $sort === $column;
    $nextDirection = ($isActive && $direction === 'asc') ? 'desc' : 'asc';
@endphp

<th {{ $attributes }}>
    @if($sortable && $column)
        <a 
            href="?sort={{ $column }}&direction={{ $nextDirection }}" 
            class="d-flex align-items-center gap-1 text-decoration-none"
            style="color: inherit;"
        >
            {{ $slot }}
            @if($isActive)
                <i class="bi bi-chevron-{{ $direction === 'asc' ? 'up' : 'down' }} small"></i>
            @else
                <i class="bi bi-chevron-expand small text-muted"></i>
            @endif
        </a>
    @else
        {{ $slot }}
    @endif
</th>
