@props(['partida', 'pulse' => false])

@php
    $classes = match ($partida->status) {
        \App\Models\Partida::STATUS_FINALIZADA => 'bg-brand-orange/15 border-brand-orange/25 text-brand-orange-sand',
        \App\Models\Partida::STATUS_AO_VIVO => 'bg-brand-asphalt/60 border-brand-blue-light/25 text-brand-blue-light',
        default => 'bg-brand-purple/15 border-brand-purple/25 text-brand-lilac',
    };
    $animate = $pulse && $partida->status === \App\Models\Partida::STATUS_AO_VIVO ? 'animate-pulse' : '';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex px-2.5 py-0.5 rounded-full border text-xs font-semibold {$classes} {$animate}"]) }}>
    {{ $partida->statusLabel() }}
</span>
