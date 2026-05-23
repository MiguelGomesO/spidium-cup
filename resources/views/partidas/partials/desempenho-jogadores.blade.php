@php
    $participacoesPorJogador = $partida->participacoes->keyBy('jogador_id');
    $jogadoresComEvento = $partida->eventos->pluck('jogador_id')->unique();
    $mvpAtual = $partida->participacoes->firstWhere('mvp', true)?->jogador_id;
@endphp

<div class="bg-brand-surface border border-brand-ice/10 rounded-2xl p-6 mt-6">
    <div class="mb-6">
        <h2 class="text-lg font-semibold">Desempenho dos jogadores</h2>
        <p class="text-sm text-brand-ice/50 mt-1">
            Marque quem jogou, atribua notas (0–10) e escolha o MVP da partida.
        </p>
    </div>

    <form method="POST" action="{{ route('partidas.participacoes.sync', $partida) }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @include('partidas.partials.desempenho-time', [
                'time' => $partida->timeCasa,
                'participacoesPorJogador' => $participacoesPorJogador,
                'jogadoresComEvento' => $jogadoresComEvento,
                'mvpAtual' => $mvpAtual,
            ])

            @include('partidas.partials.desempenho-time', [
                'time' => $partida->timeFora,
                'participacoesPorJogador' => $participacoesPorJogador,
                'jogadoresComEvento' => $jogadoresComEvento,
                'mvpAtual' => $mvpAtual,
            ])
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-2 border-t border-brand-ice/10">
            <button type="submit" class="flex-1 py-3 rounded-xl bg-brand-gradient font-semibold hover:opacity-90 transition">
                Salvar desempenho
            </button>
        </div>
    </form>
</div>
