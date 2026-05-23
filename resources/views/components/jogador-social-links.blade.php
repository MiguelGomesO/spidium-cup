@props(['jogador'])

@php
    $links = array_filter([
        'instagram' => $jogador->instagram_url,
        'twitter' => $jogador->twitter_url,
        'twitch' => $jogador->twitch_url,
    ]);
@endphp

@if (count($links) > 0)
    <div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-2']) }}>
        @if (! empty($links['instagram']))
            <a
                href="{{ $links['instagram'] }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#E4405F]/15 text-[#E4405F] border border-[#E4405F]/25 hover:bg-[#E4405F]/25 hover:scale-105 transition"
                title="Instagram: {{ $jogador->instagram }}"
                aria-label="Instagram de {{ $jogador->nome }}"
            >
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
            </a>
        @endif

        @if (! empty($links['twitter']))
            <a
                href="{{ $links['twitter'] }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-ice/10 text-brand-ice border border-brand-ice/15 hover:bg-brand-ice/20 hover:scale-105 transition"
                title="X: {{ $jogador->twitter }}"
                aria-label="X (Twitter) de {{ $jogador->nome }}"
            >
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
            </a>
        @endif

        @if (! empty($links['twitch']))
            <a
                href="{{ $links['twitch'] }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#9146FF]/15 text-[#9146FF] border border-[#9146FF]/25 hover:bg-[#9146FF]/25 hover:scale-105 transition"
                title="Twitch {{ $jogador->twitch }}"
                aria-label="Twitch de {{ $jogador->nome }}"
            >
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M11.571 4.714h1.715v5.143H11.57zm4.715 0H18v5.143h-1.714zM6 0L1.714 4.286v15.428h5.143V24l4.286-4.286h3.428L22.286 12V0zm14.571 11.143l-3.428 3.428h-3.429l-3 3v-3H6.857V1.714h13.714Z"/>
                </svg>
            </a>
        @endif
    </div>
@endif
