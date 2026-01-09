{{-- 
    Field Component - Wrapper for form inputs with label and error handling
    
    Usage:
    <x-field label="Email" name="email">
        <input type="email" name="email" class="form-control" />
    </x-field>
    
    <x-field label="Password" name="password" required hint="Must be at least 8 characters">
        <input type="password" name="password" class="form-control" />
    </x-field>
--}}

@props([
    'label' => null,
    'name' => null,
    'required' => false,
    'hint' => null,
    'inline' => false,
])

<div {{ $attributes->merge(['class' => $inline ? 'mb-3 row align-items-center' : 'mb-3']) }}>
    @if($label)
        <label class="{{ $inline ? 'col-sm-3 col-form-label' : 'form-label' }}">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    
    <div class="{{ $inline ? 'col-sm-9' : '' }}">
        {{ $slot }}
        
        @if($hint)
            <div class="form-text text-muted small">{{ $hint }}</div>
        @endif
        
        @if($name)
            @error($name)
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        @endif
    </div>
</div>
