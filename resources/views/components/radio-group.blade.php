{{-- 
    Radio Group Component - Group of radio buttons
    
    Usage:
    <x-radio-group name="plan" :options="['basic' => 'Basic Plan', 'pro' => 'Pro Plan']" selected="basic" />
    <x-radio-group name="size" :options="$sizes" inline />
--}}

@props([
    'name' => null,
    'options' => [],
    'selected' => null,
    'inline' => false,
])

<div {{ $attributes->merge(['class' => $inline ? 'd-flex gap-4 flex-wrap' : '']) }}>
    @foreach($options as $value => $label)
        <div class="form-check {{ $inline ? '' : 'mb-2' }}">
            <input 
                type="radio" 
                name="{{ $name }}" 
                id="{{ $name }}_{{ $value }}"
                value="{{ $value }}"
                class="form-check-input"
                {{ old($name, $selected) == $value ? 'checked' : '' }}
            />
            <label class="form-check-label" for="{{ $name }}_{{ $value }}">{{ $label }}</label>
        </div>
    @endforeach
</div>
