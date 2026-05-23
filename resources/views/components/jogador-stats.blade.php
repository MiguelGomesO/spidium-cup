@props(['jogador'])

@php
    $jogos = (int) ($jogador->jogos_disputados ?? 0);
    $mvps = (int) ($jogador->mvps_count ?? 0);
    $media = $jogador->media_notas;
    $mediaFormatada = $media !== null ? number_format((float) $media, 1, ',', '') : null;
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-wrap gap-2 mt-3']) }}>
    <span
        class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-brand-ice/5 border border-brand-ice/10 text-xs text-brand-ice/70"
        title="Jogos disputados"
    >
        <span aria-hidden="true">⚽</span>
        <span>{{ $jogos }} {{ $jogos === 1 ? 'jogo' : 'jogos' }}</span>
    </span>

    <span
        class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-brand-purple/10 border border-brand-purple/20 text-xs text-brand-lilac"
        title="Média de notas"
    >
        <span aria-hidden="true">★</span>
        <span>{{ $mediaFormatada ?? '—' }}</span>
    </span>

    @if ($mvps > 0)
        <span
            class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-brand-orange-sand/15 border border-brand-orange-sand/30 text-xs font-semibold text-brand-orange-sand"
            title="MVPs conquistados"
        >
            <span aria-hidden="true">🏆</span>
            <span>MVP{{ $mvps > 1 ? ' ×' . $mvps : '' }}</span>
        </span>
    @endif
</div>
