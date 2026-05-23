@php
    $participou = fn ($jogador) => $participacoesPorJogador->has($jogador->id)
        || $jogadoresComEvento->contains($jogador->id);
@endphp

<div class="rounded-2xl bg-brand-black/40 border border-brand-ice/10 p-4">
    <h3 class="font-semibold text-sm mb-4 flex items-center gap-2">
        @if ($time->logo)
            <img src="{{ asset('storage/' . $time->logo) }}" class="w-6 h-6 object-contain" alt="">
        @endif
        {{ $time->nome }}
    </h3>

    @if ($time->jogadores->isEmpty())
        <p class="text-xs text-brand-urban">Nenhum jogador no elenco.</p>
    @else
        <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
            @foreach ($time->jogadores as $jogador)
                @php
                    $participacao = $participacoesPorJogador->get($jogador->id);
                    $marcado = $participou($jogador);
                @endphp
                <div class="flex flex-col gap-2 p-3 rounded-xl bg-brand-ice/5 border border-brand-ice/5">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input
                            type="checkbox"
                            name="participantes[]"
                            value="{{ $jogador->id }}"
                            @checked($marcado)
                            class="rounded border-brand-ice/30 text-brand-orange focus:ring-brand-orange"
                        >
                        <span class="font-medium text-sm flex-1 truncate">{{ $jogador->nome }}</span>
                        @if ($jogador->numero)
                            <span class="text-xs text-brand-urban">#{{ $jogador->numero }}</span>
                        @endif
                    </label>

                    <div class="flex flex-wrap items-center gap-3 pl-7">
                        <div class="flex items-center gap-2">
                            <label for="nota-{{ $jogador->id }}" class="text-xs text-brand-urban">Nota</label>
                            <input
                                id="nota-{{ $jogador->id }}"
                                type="number"
                                name="notas[{{ $jogador->id }}]"
                                min="0"
                                max="10"
                                step="0.5"
                                placeholder="—"
                                value="{{ old('notas.'.$jogador->id, $participacao?->nota) }}"
                                class="w-16 p-1.5 rounded-lg bg-brand-black/50 border border-brand-ice/10 text-sm text-center focus:border-brand-orange outline-none"
                            >
                        </div>

                        <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs">
                            <input
                                type="radio"
                                name="mvp_jogador_id"
                                value="{{ $jogador->id }}"
                                @checked((int) old('mvp_jogador_id', $mvpAtual) === $jogador->id)
                                class="border-brand-ice/30 text-brand-orange-sand focus:ring-brand-orange-sand"
                            >
                            <span class="text-brand-orange-sand font-medium">MVP</span>
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
