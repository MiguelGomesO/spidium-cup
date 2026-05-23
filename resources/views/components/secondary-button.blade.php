<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-surface border border-brand-ice/20 rounded-md font-semibold text-xs text-brand-ice/80 uppercase tracking-widest shadow-sm hover:bg-brand-blue/30 focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-offset-2 focus:ring-offset-brand-black disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
