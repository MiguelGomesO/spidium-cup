<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-gradient border border-transparent rounded-md font-semibold text-xs text-brand-ice uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-brand-orange focus:ring-offset-2 focus:ring-offset-brand-black transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
