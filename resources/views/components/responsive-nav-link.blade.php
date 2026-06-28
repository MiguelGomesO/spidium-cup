@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-brand-orange text-start text-base font-medium text-brand-ice bg-brand-purple/20 focus:outline-none focus:text-brand-ice focus:bg-brand-purple/30 focus:border-brand-orange-sand transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-brand-urban hover:text-brand-ice hover:bg-brand-ice/5 hover:border-brand-lilac/40 focus:outline-none focus:text-brand-ice focus:bg-brand-ice/5 focus:border-brand-lilac/40 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
