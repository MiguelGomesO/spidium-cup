@props([
    'prefix' => '',
    'instagram' => null,
    'twitter' => null,
    'twitch' => null,
])

@php
    $id = fn (string $field) => $prefix ? "{$prefix}-{$field}" : "jogador-{$field}";
@endphp

<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 {{ $attributes->get('class') }}">
    <div>
        <label for="{{ $id('instagram') }}" class="block text-xs text-brand-urban mb-1">Instagram</label>
        <input
            id="{{ $id('instagram') }}"
            name="instagram"
            type="text"
            maxlength="255"
            placeholder="@usuario"
            value="{{ old('instagram', $instagram) }}"
            class="w-full p-3 rounded-xl bg-brand-black/50 border border-brand-ice/10 text-brand-ice placeholder:text-brand-urban focus:border-brand-orange focus:ring-2 focus:ring-brand-orange/25 outline-none transition"
        >
        @error('instagram')
            <p class="mt-1 text-xs text-brand-orange">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $id('twitter') }}" class="block text-xs text-brand-urban mb-1">Twitter / X</label>
        <input
            id="{{ $id('twitter') }}"
            name="twitter"
            type="text"
            maxlength="255"
            placeholder="@usuario"
            value="{{ old('twitter', $twitter) }}"
            class="w-full p-3 rounded-xl bg-brand-black/50 border border-brand-ice/10 text-brand-ice placeholder:text-brand-urban focus:border-brand-orange focus:ring-2 focus:ring-brand-orange/25 outline-none transition"
        >
        @error('twitter')
            <p class="mt-1 text-xs text-brand-orange">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $id('twitch') }}" class="block text-xs text-brand-urban mb-1">Twitch</label>
        <input
            id="{{ $id('twitch') }}"
            name="twitch"
            type="text"
            maxlength="255"
            placeholder="canal"
            value="{{ old('twitch', $twitch) }}"
            class="w-full p-3 rounded-xl bg-brand-black/50 border border-brand-ice/10 text-brand-ice placeholder:text-brand-urban focus:border-brand-orange focus:ring-2 focus:ring-brand-orange/25 outline-none transition"
        >
        @error('twitch')
            <p class="mt-1 text-xs text-brand-orange">{{ $message }}</p>
        @enderror
    </div>
</div>
