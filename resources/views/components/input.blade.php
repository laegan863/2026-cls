{{-- 
    Input Component - Styled text input with optional icon/text addons
    
    Usage:
    <x-input name="email" type="email" placeholder="Enter email" />
    <x-input name="search" prepend-icon="bi bi-search" placeholder="Search..." />
    <x-input name="amount" prepend-text="$" placeholder="0.00" />
    <x-input name="email" append-icon="bi bi-envelope" />
--}}

@props([
    'name' => null,
    'type' => 'text',
    'value' => null,
    'prependIcon' => null,
    'appendIcon' => null,
    'prependText' => null,
    'appendText' => null,
    'state' => null,
])

@php
    $hasAddon = $prependIcon || $appendIcon || $prependText || $appendText;
    $stateClass = match($state) {
        'valid' => 'is-valid',
        'invalid' => 'is-invalid',
        'warning' => 'border-warning',
        default => ''
    };
    $inputClass = 'form-control-custom ' . $stateClass;
    if ($prependIcon || $prependText) {
        $inputClass .= ' prepended';
    }
@endphp

@if($hasAddon)
    <div class="input-group-custom">
        @if($prependIcon)
            <span class="input-group-text prepend"><i class="{{ $prependIcon }}"></i></span>
        @elseif($prependText)
            <span class="input-group-text prepend">{{ $prependText }}</span>
        @endif
        <input 
            type="{{ $type }}" 
            @if($name) name="{{ $name }}" id="{{ $name }}" @endif
            value="{{ old($name ?? '', $value ?? '') }}"
            {{ $attributes->merge(['class' => $inputClass]) }}
        />
        @if($appendIcon)
            <span class="input-group-text"><i class="{{ $appendIcon }}"></i></span>
        @elseif($appendText)
            <span class="input-group-text">{{ $appendText }}</span>
        @endif
    </div>
@else
    <input 
        type="{{ $type }}" 
        @if($name) name="{{ $name }}" id="{{ $name }}" @endif
        value="{{ old($name ?? '', $value ?? '') }}"
        {{ $attributes->merge(['class' => $inputClass]) }}
    />
@endif
