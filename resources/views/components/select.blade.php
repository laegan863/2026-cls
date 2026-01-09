{{-- 
    Select Component - Styled select dropdown
    
    Usage:
    <x-select name="country" :options="['us' => 'United States', 'uk' => 'United Kingdom']" />
    <x-select name="category" placeholder="Select category">
        <option value="1">Category 1</option>
        <option value="2">Category 2</option>
    </x-select>
--}}

@props([
    'name' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => null,
])

<select 
    @if($name) name="{{ $name }}" id="{{ $name }}" @endif
    {{ $attributes->merge(['class' => 'form-select-custom']) }}
>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    
    @if(count($options) > 0)
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ old($name ?? '', $selected) == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    @else
        {{ $slot }}
    @endif
</select>
