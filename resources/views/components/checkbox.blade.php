{{-- 
    Checkbox Component - Styled checkbox input
    
    Usage:
    <x-checkbox name="terms" label="I agree to the terms" />
    <x-checkbox name="newsletter" label="Subscribe to newsletter" checked />
    <x-checkbox name="items[]" value="1" id="item_1" label="Item 1" />
--}}

@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'value' => '1',
    'checked' => false,
])

@php
    $inputId = $id ?? $name ?? 'checkbox_' . uniqid();
@endphp

<div class="form-check">
    <input 
        type="checkbox" 
        name="{{ $name }}" 
        id="{{ $inputId }}"
        value="{{ $value }}"
        {{ $checked ? 'checked' : '' }}
        {{ $attributes->merge(['class' => 'form-check-input']) }}
    />
    @if($label)
        <label class="form-check-label" for="{{ $inputId }}">{{ $label }}</label>
    @endif
</div>
