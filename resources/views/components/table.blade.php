{{-- 
    Table Component - Styled data table wrapper
    
    Usage:
    <x-table>
        <x-slot:head>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </x-slot:head>
        
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><x-status :status="$user->status" /></td>
            </tr>
        @endforeach
    </x-table>
    
    <x-table striped hover responsive>
        ...
    </x-table>
--}}

@props([
    'striped' => false,
    'hover' => true,
    'responsive' => true,
    'bordered' => false,
])

@php
    $tableClass = 'table-custom';
    if ($striped) $tableClass .= ' table-striped';
    if ($bordered) $tableClass .= ' table-bordered';
@endphp

@if($responsive)
    <div class="table-responsive">
@endif

<table {{ $attributes->merge(['class' => $tableClass]) }}>
    @isset($head)
        <thead>
            {{ $head }}
        </thead>
    @endisset
    
    <tbody>
        {{ $slot }}
    </tbody>
    
    @isset($foot)
        <tfoot>
            {{ $foot }}
        </tfoot>
    @endisset
</table>

@if($responsive)
    </div>
@endif
