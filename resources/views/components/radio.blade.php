{{-- 
    Radio Component - Styled radio input
    
    Usage:
    <x-radio name="gender" value="male" label="Male" />
    <x-radio name="gender" value="female" label="Female" />
    
    For group:
    <x-radio-group name="plan" :options="['basic' => 'Basic', 'pro' => 'Pro']" selected="basic" />
--}}

@props([
    'name' => null,
    'value' => null,
    'label' => null,
    'checked' => false,
])

<div class="form-check">
    <input 
        type="radio" 
        name="{{ $name }}" 
        id="{{ $name }}_{{ $value }}"
        value="{{ $value }}"
        {{ old($name) == $value || $checked ? 'checked' : '' }}
        {{ $attributes->merge(['class' => 'form-check-input']) }}
    />
    @if($label)
        <label class="form-check-label" for="{{ $name }}_{{ $value }}">{{ $label }}</label>
    @endif
</div>
