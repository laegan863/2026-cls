{{-- 
    File Upload Component - Styled file input
    
    Usage:
    <x-file-upload name="avatar" accept="image/*" />
    <x-file-upload name="documents" multiple />
--}}

@props([
    'name' => null,
    'accept' => null,
    'multiple' => false,
])

<input 
    type="file" 
    name="{{ $name }}" 
    id="{{ $name }}"
    {{ $accept ? "accept=$accept" : '' }}
    {{ $multiple ? 'multiple' : '' }}
    {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
/>
