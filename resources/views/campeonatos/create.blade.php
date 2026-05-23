@extends('layouts.app')

@section('content')

<div
    x-data="{
        formato: @js(old('formato', 'liga')),
        qtd_times: {{ (int) old('qtd_times', 8) }},
        nome: @js(old('nome', '')),

        get grupos() {
            if (this.formato !== 'grupos') return 0;
            return Math.max(2, Math.floor(this.qtd_times / 4));
        },

        get erro() {
            if (this.formato === 'liga' && this.qtd_times < 2) {
                return 'Liga precisa de pelo menos 2 times.';
            }
            if (this.formato === 'grupos' && this.qtd_times < 8) {
                return 'Grupos precisam de no mínimo 8 times.';
            }
            if (this.formato === 'mata_mata' && this.qtd_times % 2 !== 0) {
                return 'Mata-mata precisa de número par de times.';
            }
            return '';
        },

        get formatoLabel() {
            return {
                liga: 'Liga',
                grupos: 'Grupos',
                mata_mata: 'Mata-mata',
            }[this.formato] ?? '';
        },

        escolherFormato(f) {
            this.formato = f;
            if (f === 'grupos' && this.qtd_times < 8) this.qtd_times = 8;
            if (f === 'mata_mata' && this.qtd_times % 2 !== 0) this.qtd_times = this.qtd_times + 1;
        },

        ajustarTimes(delta) {
            const next = Math.max(2, this.qtd_times + delta);
            this.qtd_times = next;
        },
    }"
    class="max-w-5xl mx-auto space-y-8"
>
    <a
        href="{{ route('campeonatos.index') }}"
        class="inline-flex items-center gap-2 text-sm text-brand-ice/60 hover:text-brand-ice transition"
    >
        <span aria-hidden="true">←</span>
        Voltar aos campeonatos
    </a>

    <div class="relative overflow-hidden rounded-3xl border border-brand-ice/10 bg-brand-surface p-8 lg:p-10">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-orange/10 via-brand-purple/10 to-brand-blue/10"></div>
        <div class="absolute -top-16 -right-16 w-56 h-56 bg-brand-purple/20 blur-3xl rounded-full"></div>
        <div class="absolute -bottom-16 -left-16 w-56 h-56 bg-brand-orange/15 blur-3xl rounded-full"></div>

        <div class="relative z-10">
            <span class="inline-block px-3 py-1 rounded-full bg-brand-ice/10 border border-brand-ice/10 text-xs text-brand-ice/60 mb-4">
                Novo campeonato
            </span>
            <h1 class="text-3xl lg:text-4xl font-black text-brand-gradient">
                Monte seu torneio
            </h1>
            <p class="mt-3 text-brand-ice/60 max-w-xl leading-relaxed">
                Escolha o formato, defina quantos times participam e comece a organizar as partidas em poucos cliques.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
        <div class="lg:col-span-3 space-y-6">
            <form method="POST" action="{{ route('campeonatos.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="formato" :value="formato">

                <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6 lg:p-8 space-y-5">
                    <div>
                        <h2 class="text-lg font-bold text-brand-ice">1. Nome do campeonato</h2>
                        <p class="text-sm text-brand-ice/50 mt-1">Como vai aparecer para os times e no painel público.</p>
                    </div>

                    <div>
                        <label for="nome" class="sr-only">Nome</label>
                        <input
                            id="nome"
                            name="nome"
                            type="text"
                            required
                            maxlength="255"
                            x-model="nome"
                            placeholder="Ex: Spidium Cup 2026"
                            class="w-full p-4 rounded-2xl bg-brand-black/50 border border-brand-ice/10 text-brand-ice placeholder:text-brand-urban focus:border-brand-orange focus:ring-2 focus:ring-brand-orange/25 outline-none transition text-lg"
                            autocomplete="off"
                        >
                        @error('nome')
                            <p class="mt-2 text-sm text-brand-orange">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6 lg:p-8 space-y-5">
                    <div>
                        <h2 class="text-lg font-bold text-brand-ice">2. Formato</h2>
                        <p class="text-sm text-brand-ice/50 mt-1">Toque na opção que combina com o seu torneio.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <button
                            type="button"
                            @click="escolherFormato('liga')"
                            :class="formato === 'liga'
                                ? 'border-brand-blue-light bg-brand-blue/25 ring-2 ring-brand-blue-light/50'
                                : 'border-brand-ice/10 bg-brand-black/30 hover:border-brand-ice/25 hover:bg-brand-ice/5'"
                            class="text-left p-5 rounded-2xl border transition"
                        >
                            <span class="text-3xl mb-3 block" aria-hidden="true">📊</span>
                            <span class="font-bold text-brand-ice block">Liga</span>
                            <span class="text-xs text-brand-ice/50 mt-2 block leading-relaxed">Todos contra todos. Tabela por pontos.</span>
                        </button>

                        <button
                            type="button"
                            @click="escolherFormato('grupos')"
                            :class="formato === 'grupos'
                                ? 'border-brand-lilac bg-brand-purple/25 ring-2 ring-brand-lilac/50'
                                : 'border-brand-ice/10 bg-brand-black/30 hover:border-brand-ice/25 hover:bg-brand-ice/5'"
                            class="text-left p-5 rounded-2xl border transition"
                        >
                            <span class="text-3xl mb-3 block" aria-hidden="true">👥</span>
                            <span class="font-bold text-brand-ice block">Grupos</span>
                            <span class="text-xs text-brand-ice/50 mt-2 block leading-relaxed">Fase de grupos estilo copa. Mín. 8 times.</span>
                        </button>

                        <button
                            type="button"
                            @click="escolherFormato('mata_mata')"
                            :class="formato === 'mata_mata'
                                ? 'border-brand-orange bg-brand-orange/15 ring-2 ring-brand-orange/50'
                                : 'border-brand-ice/10 bg-brand-black/30 hover:border-brand-ice/25 hover:bg-brand-ice/5'"
                            class="text-left p-5 rounded-2xl border transition"
                        >
                            <span class="text-3xl mb-3 block" aria-hidden="true">🏆</span>
                            <span class="font-bold text-brand-ice block">Mata-mata</span>
                            <span class="text-xs text-brand-ice/50 mt-2 block leading-relaxed">Eliminatória direta. Número par de times.</span>
                        </button>
                    </div>

                    @error('formato')
                        <p class="text-sm text-brand-orange">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6 lg:p-8 space-y-5">
                    <div>
                        <h2 class="text-lg font-bold text-brand-ice">3. Quantidade de times</h2>
                        <p class="text-sm text-brand-ice/50 mt-1" x-show="formato === 'liga'">
                            Mínimo de 2 times para montar a tabela.
                        </p>
                        <p class="text-sm text-brand-ice/50 mt-1" x-show="formato === 'grupos'" x-cloak>
                            Recomendamos múltiplos de 4 para dividir os grupos com facilidade.
                        </p>
                        <p class="text-sm text-brand-ice/50 mt-1" x-show="formato === 'mata_mata'" x-cloak>
                            Use sempre um número par (4, 8, 16…).
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                        <button
                            type="button"
                            @click="ajustarTimes(-1)"
                            class="w-12 h-12 flex items-center justify-center shrink-0 rounded-2xl bg-brand-ice/10 border border-brand-ice/10 text-xl font-bold hover:bg-brand-ice/15 transition"
                            aria-label="Diminuir times"
                        >
                            −
                        </button>

                        <input
                            name="qtd_times"
                            type="number"
                            x-model.number="qtd_times"
                            min="2"
                            class="min-w-0 flex-1 text-center text-3xl font-black p-4 rounded-2xl bg-brand-black/50 border border-brand-ice/10 text-brand-ice focus:border-brand-blue-light focus:ring-2 focus:ring-brand-blue-light/25 outline-none transition"
                        >

                        <button
                            type="button"
                            @click="ajustarTimes(1)"
                            class="w-12 h-12 flex items-center justify-center shrink-0 rounded-2xl bg-brand-ice/10 border border-brand-ice/10 text-xl font-bold hover:bg-brand-ice/15 transition"
                            aria-label="Aumentar times"
                        >
                            +
                        </button>
                    </div>

                    <p
                        x-show="formato === 'grupos' && grupos > 0"
                        x-cloak
                        class="text-sm text-brand-lilac bg-brand-purple/15 border border-brand-purple/25 rounded-xl px-4 py-3"
                    >
                        Com <span class="font-semibold text-brand-ice" x-text="qtd_times"></span> times, serão criados cerca de
                        <span class="font-semibold text-brand-ice" x-text="grupos"></span> grupos na fase inicial.
                    </p>

                    @error('qtd_times')
                        <p class="text-sm text-brand-orange">{{ $message }}</p>
                    @enderror

                    <div
                        x-show="erro"
                        x-cloak
                        class="bg-brand-orange/10 border border-brand-orange/25 text-brand-orange-sand text-sm rounded-xl px-4 py-3 flex items-start gap-2"
                    >
                        <span aria-hidden="true">⚠</span>
                        <span x-text="erro"></span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button
                        type="submit"
                        :disabled="erro.length > 0 || !nome.trim()"
                        :class="(erro || !nome.trim()) ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90'"
                        class="flex-1 py-4 rounded-2xl font-bold bg-brand-gradient shadow-lg shadow-brand-purple/20 transition"
                    >
                        Criar campeonato
                    </button>

                    <a
                        href="{{ route('campeonatos.index') }}"
                        class="sm:w-auto px-6 py-4 rounded-2xl font-medium text-center bg-brand-ice/5 border border-brand-ice/10 hover:bg-brand-ice/10 transition"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <aside class="lg:col-span-2 lg:sticky lg:top-6">
            <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6 space-y-6">
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-brand-urban">Prévia</h3>
                    <p class="text-xs text-brand-ice/50 mt-1">Confira antes de criar</p>
                </div>

                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-brand-black/40 border border-brand-ice/5">
                        <p class="text-xs text-brand-urban mb-1">Nome</p>
                        <p class="font-bold text-lg text-brand-ice break-words" x-text="nome.trim() || '—'"></p>
                    </div>

                    <div class="p-4 rounded-2xl bg-brand-black/40 border border-brand-ice/5">
                        <p class="text-xs text-brand-urban mb-1">Formato</p>
                        <p class="font-semibold text-brand-ice" x-text="formatoLabel"></p>
                    </div>

                    <div class="p-4 rounded-2xl bg-brand-black/40 border border-brand-ice/5">
                        <p class="text-xs text-brand-urban mb-1">Vagas para times</p>
                        <p class="text-3xl font-black text-brand-orange-sand" x-text="qtd_times"></p>
                    </div>
                </div>

                <ul class="text-xs text-brand-ice/50 space-y-2 border-t border-brand-ice/10 pt-4">
                    <li class="flex gap-2">
                        <span class="text-brand-blue-light">✓</span>
                        Depois de criar, você adiciona os times e gera as partidas.
                    </li>
                    <li class="flex gap-2">
                        <span class="text-brand-blue-light">✓</span>
                        Os resultados podem ser vistos na página pública.
                    </li>
                </ul>
            </div>
        </aside>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

@endsection
