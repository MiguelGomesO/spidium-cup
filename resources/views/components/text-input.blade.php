@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-brand-urban/40 dark:border-brand-urban dark:bg-brand-black dark:text-brand-ice/80 focus:border-brand-orange dark:focus:border-brand-orange focus:ring-brand-orange dark:focus:ring-brand-orange rounded-md shadow-sm']) }}>
