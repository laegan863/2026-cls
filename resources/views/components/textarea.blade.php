{{-- 
    Textarea Component - Styled textarea
    
    Usage:
    <x-textarea name="message" rows="5" placeholder="Enter your message" />
--}}

@props([
    'name' => null,
    'rows' => 4,
    'value' => null,
])

<textarea 
    @if($name) name="{{ $name }}" id="{{ $name }}" @endif
    rows="{{ $rows }}"
    {{ $attributes->merge(['class' => 'form-control-custom']) }}
>{{ old($name ?? '', $value ?? $slot ?? '') }}</textarea>
