{{-- 
    Checkbox Component - Styled checkbox input
    
    Usage:
    <x-checkbox name="terms" label="I agree to the terms" />
    <x-checkbox name="newsletter" label="Subscribe to newsletter" checked />
--}}

@props([
    'name' => null,
    'label' => null,
    'value' => '1',
    'checked' => false,
])

<div class="form-check">
    <input 
        type="checkbox" 
        name="{{ $name }}" 
        id="{{ $name }}"
        value="{{ $value }}"
        {{ old($name) || $checked ? 'checked' : '' }}
        {{ $attributes->merge(['class' => 'form-check-input']) }}
    />
    @if($label)
        <label class="form-check-label" for="{{ $name }}">{{ $label }}</label>
    @endif
</div>
