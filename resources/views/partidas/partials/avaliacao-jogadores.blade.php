@php
    $storageKey = 'spidium_notas_' . $partida->id;
@endphp

<div
    class="section-card mt-6 relative overflow-hidden"
    x-data="{
        storageKey: @js($storageKey),
        minhasNotas: @js($notasMinhas),
        medias: @js($notasMedias),
        salvando: null,
        mensagem: '',
        erro: '',
        notasUrl: @js(route('partidas.notas.store', $partida)),
        csrf: @js(csrf_token()),

        init() {
            try {
                const salvo = JSON.parse(localStorage.getItem(this.storageKey) || '{}');
                this.minhasNotas = { ...salvo, ...this.minhasNotas };
            } catch (e) {}
        },

        notaAtual(jogadorId) {
            const id = String(jogadorId);
            return this.minhasNotas[id] ?? this.minhasNotas[jogadorId] ?? '';
        },

        jaAvaliou(jogadorId) {
            const v = this.notaAtual(jogadorId);
            return v !== '' && v !== null && v !== undefined;
        },

        mediaJogador(jogadorId) {
            const item = this.medias[jogadorId] || this.medias[String(jogadorId)];
            return item || null;
        },

        persistirLocal(jogadorId, nota) {
            const cache = JSON.parse(localStorage.getItem(this.storageKey) || '{}');
            cache[jogadorId] = nota;
            localStorage.setItem(this.storageKey, JSON.stringify(cache));
            this.minhasNotas = { ...this.minhasNotas, [jogadorId]: nota, [String(jogadorId)]: nota };
        },

        async salvarNota(jogadorId) {
            const input = this.$refs['nota-' + jogadorId];
            const nota = parseFloat(input?.value);

            if (Number.isNaN(nota) || nota < 0 || nota > 10) {
                this.erro = 'Informe uma nota entre 0 e 10.';
                return;
            }

            this.salvando = jogadorId;
            this.erro = '';
            this.mensagem = '';

            try {
                const res = await fetch(this.notasUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                    },
                    body: JSON.stringify({ jogador_id: jogadorId, nota }),
                });

                const json = await res.json();

                if (!res.ok) {
                    this.erro = json.message || Object.values(json.errors || {})[0]?.[0] || 'Não foi possível salvar a nota.';
                    return;
                }

                this.persistirLocal(jogadorId, json.nota);
                if (json.medias) this.medias = json.medias;
                this.mensagem = json.message;
            } catch (e) {
                this.erro = 'Erro de conexão. Tente novamente.';
            } finally {
                this.salvando = null;
            }
        },
    }"
>
    <div class="absolute inset-0 bg-gradient-to-br from-brand-blue/10 via-transparent to-brand-purple/10 pointer-events-none"></div>

    <div class="relative z-10">
        <div class="mb-6">
            <span class="inline-block px-3 py-1 rounded-full bg-brand-blue-light/10 border border-brand-blue-light/20 text-xs text-brand-blue-light mb-3">
                Avaliação pública
            </span>
            <h2 class="text-lg sm:text-xl font-bold">Notas dos jogadores</h2>
            <p class="text-sm text-brand-ice/50 mt-1 max-w-xl">
                Dê uma nota de 0 a 10 para cada jogador. Você pode alterar sua nota quando quiser (uma avaliação por jogador, por dispositivo e IP).
            </p>
        </div>

        <p x-show="mensagem" x-text="mensagem" class="mb-4 text-sm text-success" x-cloak></p>
        <p x-show="erro" x-text="erro" class="mb-4 text-sm text-red-400" x-cloak></p>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach ([$partida->timeCasa, $partida->timeFora] as $time)
                <div class="rounded-2xl border border-brand-ice/10 bg-brand-black/30 p-4">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-brand-ice/10">
                        @if ($time->logo)
                            <img src="{{ asset('storage/' . $time->logo) }}" alt="" class="w-10 h-10 rounded-full object-cover border border-brand-ice/20">
                        @endif
                        <h3 class="font-semibold">{{ $time->nome }}</h3>
                    </div>

                    @if ($time->jogadores->isEmpty())
                        <p class="text-sm text-brand-ice/40 text-center py-4">Nenhum jogador cadastrado.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach ($time->jogadores as $jogador)
                                <li
                                    class="p-4 rounded-xl border border-brand-ice/10 bg-brand-ice/5"
                                    x-init="if (jaAvaliou({{ $jogador->id }})) {
                                        const n = parseFloat(notaAtual({{ $jogador->id }}));
                                        if ($refs['nota-{{ $jogador->id }}']) $refs['nota-{{ $jogador->id }}'].value = n;
                                        if ($refs['label-{{ $jogador->id }}']) $refs['label-{{ $jogador->id }}'].textContent = n.toFixed(1);
                                    }"
                                >
                                    <div class="flex flex-wrap items-start justify-between gap-2 mb-3">
                                        <div class="min-w-0">
                                            <p class="font-medium truncate">{{ $jogador->nome }}</p>
                                            @if ($jogador->numero)
                                                <p class="text-xs text-brand-urban">#{{ $jogador->numero }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right text-xs text-brand-ice/50" x-show="mediaJogador({{ $jogador->id }})" x-cloak>
                                            <span class="text-brand-blue-light font-semibold" x-text="mediaJogador({{ $jogador->id }})?.media?.toFixed(1)"></span>
                                            <span> média</span>
                                            <span class="text-brand-urban">(<span x-text="mediaJogador({{ $jogador->id }})?.total"></span>)</span>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <div class="flex items-center gap-3">
                                            <input
                                                type="range"
                                                min="0"
                                                max="10"
                                                step="0.5"
                                                :value="notaAtual({{ $jogador->id }}) || 5"
                                                @input="$refs['nota-{{ $jogador->id }}'].value = $event.target.value; $refs['label-{{ $jogador->id }}'].textContent = parseFloat($event.target.value).toFixed(1)"
                                                class="flex-1 h-2 rounded-full appearance-none bg-brand-ice/10 accent-brand-orange cursor-pointer"
                                            >
                                            <input
                                                type="number"
                                                x-ref="nota-{{ $jogador->id }}"
                                                min="0"
                                                max="10"
                                                step="0.5"
                                                :value="notaAtual({{ $jogador->id }})"
                                                class="w-16 p-2 rounded-lg bg-brand-black/50 border border-brand-ice/10 text-sm text-center focus:border-brand-orange outline-none"
                                            >
                                        </div>

                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                            <p class="text-xs text-brand-ice/50">
                                                Prévia: <span x-ref="label-{{ $jogador->id }}" class="font-semibold text-brand-orange-sand" x-text="notaAtual({{ $jogador->id }}) !== '' ? parseFloat(notaAtual({{ $jogador->id }})).toFixed(1) : '—'"></span>
                                            </p>
                                            <button
                                                type="button"
                                                @click="salvarNota({{ $jogador->id }})"
                                                :disabled="salvando === {{ $jogador->id }}"
                                                class="min-h-[40px] px-4 rounded-xl text-sm font-medium transition disabled:opacity-50"
                                                :class="jaAvaliou({{ $jogador->id }})
                                                    ? 'bg-brand-blue-light/15 border border-brand-blue-light/30 text-brand-blue-light hover:bg-brand-blue-light/25'
                                                    : 'bg-brand-gradient text-brand-ice hover:opacity-90'"
                                            >
                                                <span x-show="salvando !== {{ $jogador->id }}" x-text="jaAvaliou({{ $jogador->id }}) ? 'Atualizar nota' : 'Salvar nota'"></span>
                                                <span x-show="salvando === {{ $jogador->id }}" x-cloak>Salvando…</span>
                                            </button>
                                        </div>

                                        <p
                                            x-show="jaAvaliou({{ $jogador->id }})"
                                            class="text-xs text-brand-blue-light/80"
                                            x-cloak
                                        >
                                            Você já avaliou este jogador (nota <span x-text="parseFloat(notaAtual({{ $jogador->id }})).toFixed(1)"></span>). Pode alterar quando quiser.
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
