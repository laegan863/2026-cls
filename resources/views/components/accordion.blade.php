{{-- 
    Accordion Component - Collapsible accordion
    
    Usage:
    <x-accordion id="myAccordion">
        <x-accordion-item id="one" title="Section One" show>
            Content for section one
        </x-accordion-item>
        <x-accordion-item id="two" title="Section Two">
            Content for section two
        </x-accordion-item>
    </x-accordion>
--}}

@props([
    'id' => 'accordion-' . uniqid(),
    'flush' => false,
])

<div {{ $attributes->merge(['class' => 'accordion' . ($flush ? ' accordion-flush' : ''), 'id' => $id]) }} data-accordion-id="{{ $id }}">
    {{ $slot }}
</div>
