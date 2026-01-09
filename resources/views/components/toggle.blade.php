{{-- 
    Toggle/Switch Component - Styled toggle switch
    
    Usage:
    <x-toggle name="notifications" label="Enable notifications" />
    <x-toggle name="dark_mode" label="Dark Mode" checked />
--}}

@props([
    'name' => null,
    'label' => null,
    'value' => '1',
    'checked' => false,
])

<div class="form-check form-switch">
    <input 
        type="checkbox" 
        name="{{ $name }}" 
        id="{{ $name }}"
        value="{{ $value }}"
        role="switch"
        {{ old($name) || $checked ? 'checked' : '' }}
        {{ $attributes->merge(['class' => 'form-check-input']) }}
    />
    @if($label)
        <label class="form-check-label" for="{{ $name }}">{{ $label }}</label>
    @endif
</div>
