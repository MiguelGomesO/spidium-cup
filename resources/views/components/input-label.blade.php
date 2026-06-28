@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-brand-ice/80']) }}>
    {{ $value ?? $slot }}
</label>
