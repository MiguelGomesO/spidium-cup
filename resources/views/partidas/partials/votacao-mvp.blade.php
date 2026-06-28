@php
    $storageKey = 'spidium_mvp_voto_' . $partida->id;
    $consentKey = 'spidium_voto_ip_consent_v1';
@endphp

<div
    class="section-card mt-6 relative overflow-hidden"
    x-data="{
        partidaId: {{ $partida->id }},
        storageKey: @js($storageKey),
        consentKey: @js($consentKey),
        consentAceito: localStorage.getItem(@js($consentKey)) === '1',
        modalConsentimento: false,
        votoPendente: null,
        jaVotou: @js($votacaoIpJaVotou) || localStorage.getItem(@js($storageKey)) !== null,
        jogadorVotadoId: localStorage.getItem(@js($storageKey)),
        votando: false,
        fingerprintPronto: false,
        visitorId: null,
        mensagem: '',
        erro: '',
        ranking: @js($votacaoRanking),
        mvp: @js($votacaoMvp),
        totalVotos: {{ $votacaoTotal }},
        votosUrl: @js(route('partidas.votos.store', $partida)),
        csrf: @js(csrf_token()),

        async init() {
            if (this.consentAceito) {
                await this.carregarFingerprint();
            }
        },

        async carregarFingerprint() {
            if (this.fingerprintPronto) return;

            if (typeof window.getVisitorId !== 'function') {
                this.erro = 'Não foi possível identificar este dispositivo. Recarregue a página.';
                return;
            }

            try {
                this.visitorId = await window.getVisitorId();
                this.fingerprintPronto = true;
            } catch (e) {
                this.erro = 'Não foi possível identificar este dispositivo. Recarregue a página.';
            }
        },

        votosDoJogador(jogadorId) {
            const item = this.ranking.find(r => r.jogador_id === jogadorId);
            return item ? item.total : 0;
        },

        solicitarVoto(jogadorId) {
            if (this.jaVotou || this.votando) return;

            if (! this.consentAceito) {
                this.votoPendente = jogadorId;
                this.modalConsentimento = true;
                return;
            }

            this.votar(jogadorId);
        },

        recusarConsentimento() {
            this.modalConsentimento = false;
            this.votoPendente = null;
        },

        async aceitarConsentimento() {
            localStorage.setItem(this.consentKey, '1');
            this.consentAceito = true;
            this.modalConsentimento = false;

            await this.carregarFingerprint();

            if (this.votoPendente !== null) {
                const jogadorId = this.votoPendente;
                this.votoPendente = null;
                await this.votar(jogadorId);
            }
        },

        async votar(jogadorId) {
            if (this.jaVotou || this.votando || ! this.consentAceito) return;

            if (! this.fingerprintPronto || ! this.visitorId) {
                await this.carregarFingerprint();
                if (! this.fingerprintPronto || ! this.visitorId) return;
            }

            this.votando = true;
            this.erro = '';
            this.mensagem = '';

            try {
                const res = await fetch(this.votosUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                    },
                    body: JSON.stringify({
                        jogador_id: jogadorId,
                        visitor_id: this.visitorId,
                    }),
                });

                const json = await res.json();

                if (res.status === 422 && json.ja_votou) {
                    this.jaVotou = true;
                    if (json.jogador_votado_id) {
                        this.jogadorVotadoId = String(json.jogador_votado_id);
                        localStorage.setItem(this.storageKey, this.jogadorVotadoId);
                    }
                    this.erro = json.message || 'Você já votou nesta partida.';
                    this.atualizarEstatisticas(json);
                    return;
                }

                if (res.status === 429) {
                    this.erro = json.message || 'Muitas tentativas. Aguarde um momento e tente novamente.';
                    return;
                }

                if (!res.ok) {
                    this.erro = json.message || Object.values(json.errors || {})[0]?.[0] || 'Não foi possível registrar o voto.';
                    return;
                }

                localStorage.setItem(this.storageKey, String(jogadorId));
                this.jogadorVotadoId = String(jogadorId);
                this.jaVotou = true;
                this.mensagem = json.message || 'Voto registrado!';
                this.atualizarEstatisticas(json);
            } catch (e) {
                this.erro = 'Erro de conexão. Tente novamente.';
            } finally {
                this.votando = false;
            }
        },

        atualizarEstatisticas(json) {
            if (json.ranking) this.ranking = json.ranking;
            if (json.mvp !== undefined) this.mvp = json.mvp;
            if (json.total_votos !== undefined) this.totalVotos = json.total_votos;
        },
    }"
    x-init="init()"
    @keydown.escape.window="modalConsentimento && recusarConsentimento()"
>
    <div class="absolute inset-0 bg-gradient-to-br from-brand-purple/15 via-transparent to-brand-orange/10 pointer-events-none"></div>

    <div class="relative z-10">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-brand-orange/15 border border-brand-orange/25 text-xs text-brand-orange-sand mb-3">
                    Votação pública
                </span>
                <h2 class="text-lg sm:text-xl font-bold">MVP da partida</h2>
                <p class="text-sm text-brand-ice/50 mt-1 max-w-xl">
                    Escolha o melhor jogador da partida. Cada visitante pode votar uma vez por dispositivo.
                    <span class="block mt-1 text-brand-urban/90">Na primeira votação, pediremos seu consentimento para registrar o endereço IP.</span>
                </p>
            </div>
            <div class="stat-box shrink-0 text-center sm:text-right min-w-[120px]">
                <p class="stat-box__label">Total de votos</p>
                <p class="stat-box__value text-brand-orange-sand" x-text="totalVotos">0</p>
            </div>
        </div>

        <template x-if="mvp && totalVotos > 0">
            <div class="mb-6 p-4 sm:p-5 rounded-2xl border border-brand-orange/30 bg-brand-orange/10">
                <p class="text-xs uppercase tracking-wide text-brand-orange-sand mb-2">🏆 MVP da partida</p>
                <template x-if="!mvp?.empate">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-lg sm:text-xl font-bold" x-text="mvp.nome"></p>
                            <p class="text-sm text-brand-ice/60" x-text="mvp.time_nome"></p>
                        </div>
                        <p class="text-2xl font-black text-brand-orange-sand">
                            <span x-text="mvp.total"></span>
                            <span class="text-sm font-medium text-brand-ice/50" x-text="mvp.total === 1 ? ' voto' : ' votos'"></span>
                        </p>
                    </div>
                </template>
                <template x-if="mvp?.empate">
                    <div>
                        <p class="text-sm text-brand-ice/70 mb-2">Empate entre os jogadores:</p>
                        <ul class="space-y-2">
                            <template x-for="j in mvp.empatados" :key="j.jogador_id">
                                <li class="flex justify-between text-sm sm:text-base">
                                    <span><span class="font-semibold" x-text="j.nome"></span> · <span class="text-brand-ice/50" x-text="j.time_nome"></span></span>
                                    <span class="font-bold text-brand-orange-sand" x-text="j.total + ' votos'"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="jaVotou">
            <div class="mb-5 px-4 py-3 rounded-xl bg-brand-blue/20 border border-brand-blue-light/25 text-sm text-brand-blue-light">
                Você já votou nesta partida.
            </div>
        </template>

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
                        <ul class="space-y-2">
                            @foreach ($time->jogadores as $jogador)
                                <li>
                                    <button
                                        type="button"
                                        @click="solicitarVoto({{ $jogador->id }})"
                                        :disabled="jaVotou || votando || (consentAceito && !fingerprintPronto)"
                                        :class="jaVotou
                                            ? 'opacity-60 cursor-not-allowed border-brand-ice/10 bg-brand-ice/5'
                                            : 'hover:border-brand-orange/40 hover:bg-brand-orange/10 border-brand-ice/10 active:scale-[0.99]'"
                                        class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl border transition text-left min-h-[52px]"
                                    >
                                        <span class="flex items-center gap-3 min-w-0">
                                            <span class="w-8 h-8 shrink-0 rounded-lg bg-brand-purple/30 flex items-center justify-center text-xs font-bold text-brand-ice/80">
                                                {{ $jogador->numero ?? '—' }}
                                            </span>
                                            <span class="font-medium truncate">{{ $jogador->nome }}</span>
                                            <span
                                                x-show="jogadorVotadoId == '{{ $jogador->id }}'"
                                                class="shrink-0 text-[10px] uppercase tracking-wide text-brand-orange-sand"
                                                x-cloak
                                            >seu voto</span>
                                        </span>
                                        <span class="shrink-0 text-xs text-brand-ice/50 tabular-nums">
                                            <span x-text="votosDoJogador({{ $jogador->id }})"></span> votos
                                        </span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Modal: consentimento IP (primeira votação) --}}
    <template x-teleport="body">
        <div
            x-show="modalConsentimento"
            x-cloak
            class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="voto-consent-title"
        >
            <div
                @click="recusarConsentimento()"
                class="absolute inset-0 bg-brand-black/80 backdrop-blur-md"
                x-show="modalConsentimento"
                x-transition.opacity
            ></div>

            <div
                class="relative w-full sm:max-w-md bg-brand-surface border border-brand-ice/10 rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden"
                x-show="modalConsentimento"
                x-transition
                @click.stop
            >
                <div class="p-6 sm:p-8">
                    <div class="w-12 h-12 rounded-2xl bg-brand-orange/15 border border-brand-orange/25 flex items-center justify-center text-2xl mb-4">
                        🔒
                    </div>

                    <h3 id="voto-consent-title" class="text-xl font-bold text-brand-ice mb-2">
                        Antes de votar
                    </h3>

                    <p class="text-sm text-brand-ice/60 leading-relaxed mb-4">
                        Para garantir <strong class="text-brand-ice/80 font-semibold">um voto por pessoa</strong>, o Spidium Cup registra:
                    </p>

                    <ul class="text-sm text-brand-ice/60 space-y-2 mb-6 pl-1">
                        <li class="flex gap-2">
                            <span class="text-brand-orange-sand shrink-0">•</span>
                            <span>Um <strong class="text-brand-ice/80 font-medium">identificador do seu dispositivo</strong> (fingerprint anônimo)</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-brand-orange-sand shrink-0">•</span>
                            <span>Um <strong class="text-brand-ice/80 font-medium">hash do seu endereço IP</strong> — não armazenamos o IP em texto puro</span>
                        </li>
                    </ul>

                    <p class="text-xs text-brand-urban leading-relaxed mb-6">
                        Esses dados servem apenas para evitar votos duplicados nesta votação de MVP. Ao aceitar, você concorda com esse uso. Você precisa aceitar para registrar seu voto.
                    </p>

                    <div class="flex flex-col-reverse sm:flex-row gap-3">
                        <button
                            type="button"
                            @click="recusarConsentimento()"
                            class="btn-ghost flex-1 text-sm"
                        >
                            Agora não
                        </button>
                        <button
                            type="button"
                            @click="aceitarConsentimento()"
                            class="btn-brand flex-1 text-sm"
                        >
                            Aceito e quero votar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
