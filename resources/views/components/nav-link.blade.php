@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-brand-orange text-sm font-medium leading-5 text-brand-ice focus:outline-none focus:border-brand-orange-sand transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-brand-urban hover:text-brand-ice/80 hover:border-brand-lilac/40 focus:outline-none focus:text-brand-ice/80 focus:border-brand-lilac/40 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
