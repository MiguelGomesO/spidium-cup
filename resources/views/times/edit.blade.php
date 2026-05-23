@extends('layouts.app')

@section('content')

@php
    $imageFields = [
        'logo' => ['label' => 'Escudo', 'hint' => 'PNG ou JPG, até 5 MB', 'icon' => '🛡️'],
        'uniforme' => ['label' => 'Uniforme', 'hint' => 'Opcional', 'icon' => '👕'],
        'estadio' => ['label' => 'Estádio', 'hint' => 'Opcional', 'icon' => '🏟️'],
    ];
@endphp

<div
    class="max-w-6xl mx-auto space-y-8"
    x-data="timeEdit({
        timeId: {{ $time->id }},
        updateUrl: @js(route('times.update', $time)),
        nome: @js($time->nome),
        existing: {
            logo: @js($time->logo ? asset('storage/' . $time->logo) : null),
            uniforme: @js($time->uniforme ? asset('storage/' . $time->uniforme) : null),
            estadio: @js($time->estadio ? asset('storage/' . $time->estadio) : null),
        },
    })"
>
    <a
        href="{{ route('times.index') }}"
        class="inline-flex items-center gap-2 text-sm text-brand-ice/60 hover:text-brand-ice transition"
    >
        <span aria-hidden="true">←</span>
        Voltar aos times
    </a>

    @if (session('success'))
        <div class="bg-brand-asphalt/50 border border-brand-blue-light/25 text-brand-blue-light text-sm rounded-2xl px-5 py-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-3xl border border-brand-ice/10 bg-brand-surface p-6 lg:p-8">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-blue/15 via-brand-purple/10 to-brand-orange/10"></div>
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-brand-purple/20 blur-3xl rounded-full"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center gap-6">
            <div class="flex items-center gap-5 min-w-0 flex-1">
                <div class="w-20 h-20 shrink-0 rounded-2xl bg-brand-black/50 border border-brand-ice/10 flex items-center justify-center overflow-hidden p-2">
                    <template x-if="imageUrl('logo')">
                        <img :src="imageUrl('logo')" alt="" class="w-full h-full object-contain">
                    </template>
                    <template x-if="!imageUrl('logo')">
                        <span class="text-3xl" aria-hidden="true">⚽</span>
                    </template>
                </div>

                <div class="min-w-0 flex-1">
                    <span class="inline-block px-3 py-1 rounded-full bg-brand-ice/10 border border-brand-ice/10 text-xs text-brand-ice/60 mb-2">
                        Editar time
                    </span>

                    <template x-if="!editingNome">
                        <button
                            type="button"
                            @click="startEditNome()"
                            class="group text-left w-full"
                            title="Clique para editar o nome"
                        >
                            <h1 class="text-2xl lg:text-3xl font-black text-brand-gradient truncate group-hover:opacity-90 transition" x-text="nome"></h1>
                            <p class="text-xs text-brand-urban mt-1 group-hover:text-brand-blue-light transition">Clique no nome para editar</p>
                        </button>
                    </template>

                    <template x-if="editingNome">
                        <div class="space-y-2">
                            <input
                                type="text"
                                x-model="nome"
                                x-ref="nomeInput"
                                maxlength="255"
                                @keydown.enter.prevent="saveNome()"
                                @keydown.escape.prevent="cancelEditNome()"
                                class="w-full text-2xl lg:text-3xl font-black bg-brand-black/50 border border-brand-orange/40 rounded-xl px-4 py-2 outline-none focus:ring-2 focus:ring-brand-orange/25 text-brand-ice"
                            >
                            <div class="flex flex-wrap gap-2">
                                <button
                                    type="button"
                                    @click="saveNome()"
                                    :disabled="savingNome"
                                    class="px-4 py-2 rounded-xl bg-brand-gradient text-sm font-semibold hover:opacity-90 transition disabled:opacity-50"
                                >
                                    <span x-show="!savingNome">Salvar nome</span>
                                    <span x-show="savingNome">Salvando…</span>
                                </button>
                                <button
                                    type="button"
                                    @click="cancelEditNome()"
                                    class="px-4 py-2 rounded-xl bg-brand-ice/5 border border-brand-ice/10 text-sm hover:bg-brand-ice/10 transition"
                                >
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 lg:justify-end shrink-0">
                <a
                    href="{{ route('times.escalacao', $time) }}"
                    class="px-4 py-2.5 rounded-2xl bg-brand-ice/10 border border-brand-ice/10 text-sm font-medium hover:bg-brand-ice/15 transition"
                >
                    Escalação
                </a>
                <button
                    type="button"
                    @click="openHistorico()"
                    :disabled="loadingHistorico"
                    class="px-4 py-2.5 rounded-2xl bg-brand-ice/10 border border-brand-ice/10 text-sm font-medium hover:border-brand-blue-light/40 transition disabled:opacity-50"
                >
                    Histórico
                </button>
                <button
                    type="button"
                    @click="openArtilheiros()"
                    :disabled="loadingArtilheiros"
                    class="px-4 py-2.5 rounded-2xl bg-brand-gradient text-sm font-semibold hover:opacity-90 transition disabled:opacity-50"
                >
                    Artilheiros
                </button>
            </div>
        </div>

        <div class="relative z-10 grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-6 border-t border-brand-ice/10">
            <div class="rounded-xl bg-brand-black/40 px-4 py-3">
                <p class="text-xs text-brand-urban">Jogadores</p>
                <p class="text-2xl font-black text-brand-blue-light">{{ $time->jogadores_count }}</p>
            </div>
            <div class="rounded-xl bg-brand-black/40 px-4 py-3">
                <p class="text-xs text-brand-urban">Campeonatos</p>
                <p class="text-2xl font-black text-brand-orange-sand">{{ $time->campeonatos_count }}</p>
            </div>
            <div class="rounded-xl bg-brand-black/40 px-4 py-3 col-span-2">
                <p class="text-xs text-brand-urban">Dica</p>
                <p class="text-xs text-brand-ice/60 mt-1 leading-relaxed">Imagens são salvas automaticamente ao enviar. Arraste ou clique nos cards abaixo.</p>
            </div>
        </div>
    </div>

    {{-- Imagens --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($imageFields as $campo => $meta)
            <div
                class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6 space-y-4 transition"
                :class="dragging === '{{ $campo }}' ? 'border-brand-purple/50 bg-brand-purple/5 scale-[1.02]' : ''"
                @dragover.prevent="dragging = '{{ $campo }}'"
                @dragleave="dragging = null"
                @drop.prevent="handleDrop($event, '{{ $campo }}'); dragging = null"
            >
                <div>
                    <h2 class="text-lg font-bold flex items-center gap-2">
                        <span aria-hidden="true">{{ $meta['icon'] }}</span>
                        {{ $meta['label'] }}
                    </h2>
                    <p class="text-xs text-brand-urban mt-1">{{ $meta['hint'] }}</p>
                </div>

                <input
                    type="file"
                    accept="image/*"
                    class="hidden"
                    x-ref="file_{{ $campo }}"
                    @change="handleFileSelect($event, '{{ $campo }}')"
                >

                <button
                    type="button"
                    @click="$refs.file_{{ $campo }}.click()"
                    class="relative w-full group"
                >
                    <div
                        x-show="loading === '{{ $campo }}'"
                        class="absolute inset-0 z-10 flex items-center justify-center rounded-2xl bg-brand-black/70"
                    >
                        <div class="animate-spin rounded-full h-8 w-8 border-2 border-brand-ice border-t-transparent"></div>
                    </div>

                    <div
                        x-show="dragging === '{{ $campo }}'"
                        class="absolute inset-0 z-10 flex items-center justify-center rounded-2xl bg-brand-black/80 border-2 border-dashed border-brand-lilac"
                    >
                        <span class="text-sm font-medium text-brand-lilac">Solte aqui</span>
                    </div>

                    <div class="flex flex-col items-center justify-center gap-3 p-6 min-h-[180px] rounded-2xl border-2 border-dashed border-brand-ice/15 bg-brand-black/30 group-hover:border-brand-blue-light/40 group-hover:bg-brand-blue/5 transition">
                        <template x-if="imageUrl('{{ $campo }}')">
                            <img
                                :src="imageUrl('{{ $campo }}')"
                                alt="{{ $meta['label'] }}"
                                class="max-h-28 w-auto object-contain"
                            >
                        </template>
                        <template x-if="!imageUrl('{{ $campo }}')">
                            <div class="w-24 h-24 rounded-2xl bg-brand-ice/5 border border-brand-ice/10 flex items-center justify-center text-4xl">
                                {{ $meta['icon'] }}
                            </div>
                        </template>
                        <span class="text-sm text-brand-blue-light font-medium">Trocar {{ strtolower($meta['label']) }}</span>
                    </div>
                </button>
            </div>
        @endforeach
    </div>

    {{-- Elenco --}}
    <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6 lg:p-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Elenco</h2>
                <p class="text-sm text-brand-ice/50 mt-1">Adicione jogadores para súmulas e estatísticas</p>
            </div>
            <span class="text-sm text-brand-urban">{{ $time->jogadores_count }} {{ $time->jogadores_count === 1 ? 'jogador' : 'jogadores' }}</span>
        </div>

        <form
            method="POST"
            action="{{ route('jogadores.store', $time) }}"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 p-4 rounded-2xl bg-brand-black/40 border border-brand-ice/10"
        >
            @csrf

            <div class="lg:col-span-5">
                <label for="jogador-nome" class="sr-only">Nome</label>
                <input
                    id="jogador-nome"
                    name="nome"
                    type="text"
                    required
                    maxlength="255"
                    placeholder="Nome do jogador"
                    value="{{ old('nome') }}"
                    class="w-full p-3 rounded-xl bg-brand-black/50 border border-brand-ice/10 text-brand-ice placeholder:text-brand-urban focus:border-brand-orange focus:ring-2 focus:ring-brand-orange/25 outline-none transition"
                >
                @error('nome')
                    <p class="mt-1 text-xs text-brand-orange">{{ $message }}</p>
                @enderror
            </div>

            <div class="lg:col-span-4">
                <label for="jogador-posicao" class="sr-only">Posição</label>
                <select
                    id="jogador-posicao"
                    name="posicao"
                    class="w-full p-3 rounded-xl bg-brand-black/50 border border-brand-ice/10 text-brand-ice focus:border-brand-orange focus:ring-2 focus:ring-brand-orange/25 outline-none transition"
                >
                    <option value="">Posição</option>
                    @foreach (['Goleiro', 'Zagueiro', 'Lateral', 'Volante', 'Meia', 'Atacante'] as $pos)
                        <option value="{{ $pos }}" @selected(old('posicao') === $pos)>{{ $pos }}</option>
                    @endforeach
                </select>
                @error('posicao')
                    <p class="mt-1 text-xs text-brand-orange">{{ $message }}</p>
                @enderror
            </div>

            <div class="lg:col-span-1">
                <label for="jogador-numero" class="sr-only">Número</label>
                <input
                    id="jogador-numero"
                    name="numero"
                    type="number"
                    min="0"
                    max="99"
                    placeholder="#"
                    value="{{ old('numero') }}"
                    class="w-full p-3 rounded-xl bg-brand-black/50 border border-brand-ice/10 text-brand-ice placeholder:text-brand-urban focus:border-brand-orange focus:ring-2 focus:ring-brand-orange/25 outline-none transition text-center"
                >
                @error('numero')
                    <p class="mt-1 text-xs text-brand-orange">{{ $message }}</p>
                @enderror
            </div>

            <div class="lg:col-span-2">
                <button
                    type="submit"
                    class="w-full h-full min-h-[48px] px-4 rounded-xl bg-brand-gradient font-semibold hover:opacity-90 transition"
                >
                    Adicionar
                </button>
            </div>
        </form>

        @if ($time->jogadores->isEmpty())
            <div class="text-center py-12 rounded-2xl border border-dashed border-brand-ice/10">
                <p class="text-brand-ice/40 mb-1">Nenhum jogador no elenco</p>
                <p class="text-xs text-brand-urban">Use o formulário acima para cadastrar o primeiro jogador</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($time->jogadores as $jogador)
                    <article class="relative group rounded-2xl border border-brand-ice/10 bg-brand-black/40 p-4 hover:border-brand-lilac/30 transition">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 shrink-0 rounded-xl bg-brand-purple/20 border border-brand-purple/30 flex items-center justify-center font-black text-lg text-brand-lilac">
                                {{ $jogador->numero ?? '—' }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold truncate">{{ $jogador->nome }}</p>
                                <p class="text-xs text-brand-urban uppercase tracking-wide mt-0.5">
                                    {{ $jogador->posicao ?? 'Sem posição' }}
                                </p>
                            </div>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('jogadores.destroy', $jogador) }}"
                            class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition"
                            onsubmit="return confirm('Remover {{ addslashes($jogador->nome) }} do elenco?')"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="w-8 h-8 rounded-lg bg-brand-orange/20 text-brand-orange hover:bg-brand-orange/30 text-sm transition"
                                title="Remover jogador"
                                aria-label="Remover {{ $jogador->nome }}"
                            >
                                ✕
                            </button>
                        </form>
                    </article>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Modal histórico --}}
    <div
            x-show="showHistorico"
            x-cloak
            class="fixed inset-0 z-[200] flex items-center justify-center p-4"
            @keydown.escape.window="showHistorico = false"
        >
            <div class="absolute inset-0 bg-brand-black/80 backdrop-blur-sm" @click="showHistorico = false"></div>

            <div
                class="relative w-full max-w-2xl max-h-[85vh] flex flex-col bg-brand-surface border border-brand-ice/10 rounded-3xl shadow-2xl overflow-hidden"
                @click.stop
            >
                <div class="p-6 border-b border-brand-ice/10 shrink-0">
                    <h2 class="text-xl font-bold">Histórico de partidas</h2>
                    <p class="text-sm text-brand-urban mt-1">Todas as partidas com este time</p>
                </div>

                <div class="overflow-y-auto p-6 space-y-3 flex-1">
                    <template x-if="loadingHistorico">
                        <p class="text-center text-brand-urban py-8">Carregando…</p>
                    </template>

                    <template x-if="!loadingHistorico && historico.length === 0">
                        <p class="text-center text-brand-ice/40 py-8">Nenhuma partida registrada ainda.</p>
                    </template>

                    <template x-for="p in historico" :key="p.id">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-4 rounded-2xl bg-brand-black/40 border border-brand-ice/5">
                            <div class="min-w-0">
                                <p class="text-xs text-brand-blue-light font-medium" x-text="formatDate(p.data)"></p>
                                <p class="text-sm mt-1 truncate">
                                    <span x-text="p.time_casa?.nome"></span>
                                    <span class="text-brand-urban"> vs </span>
                                    <span x-text="p.time_fora?.nome"></span>
                                </p>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span
                                    class="text-xs font-semibold px-2 py-0.5 rounded-full"
                                    :class="{
                                        'bg-brand-blue-light/20 text-brand-blue-light': resultadoPartida(p) === 'draw',
                                        'bg-brand-orange-sand/20 text-brand-orange-sand': resultadoPartida(p) === 'win',
                                        'bg-brand-orange/20 text-brand-orange': resultadoPartida(p) === 'loss',
                                    }"
                                    x-text="resultadoLabel(p)"
                                ></span>
                                <span class="text-xl font-black tabular-nums text-brand-orange-sand" x-text="p.gols_casa + ' × ' + p.gols_fora"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="p-4 border-t border-brand-ice/10 shrink-0">
                    <button
                        type="button"
                        @click="showHistorico = false"
                        class="w-full py-3 rounded-2xl bg-brand-ice/10 hover:bg-brand-ice/15 font-medium transition"
                    >
                        Fechar
                    </button>
                </div>
            </div>
        </div>

    {{-- Modal artilheiros --}}
    <div
            x-show="showArtilheiros"
            x-cloak
            class="fixed inset-0 z-[200] flex items-center justify-center p-4"
            @keydown.escape.window="showArtilheiros = false"
        >
            <div class="absolute inset-0 bg-brand-black/80 backdrop-blur-sm" @click="showArtilheiros = false"></div>

            <div
                class="relative w-full max-w-lg max-h-[85vh] flex flex-col bg-brand-surface border border-brand-ice/10 rounded-3xl shadow-2xl overflow-hidden"
                @click.stop
            >
                <div class="p-6 border-b border-brand-ice/10 shrink-0">
                    <h2 class="text-xl font-bold">Artilheiros do time</h2>
                    <p class="text-sm text-brand-urban mt-1">Gols registrados nas súmulas</p>
                </div>

                <div class="overflow-y-auto p-6 space-y-3 flex-1">
                    <template x-if="loadingArtilheiros">
                        <p class="text-center text-brand-urban py-8">Carregando…</p>
                    </template>

                    <template x-if="!loadingArtilheiros && artilheiros.length === 0">
                        <p class="text-center text-brand-ice/40 py-8">Nenhum gol registrado ainda.</p>
                    </template>

                    <template x-for="(j, index) in artilheiros" :key="j.id">
                        <div class="flex items-center gap-3 p-4 rounded-2xl bg-brand-black/40">
                            <span
                                class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold shrink-0"
                                :class="index === 0 ? 'bg-brand-orange-sand/20 text-brand-orange-sand' : 'bg-brand-ice/10 text-brand-ice/60'"
                                x-text="index + 1"
                            ></span>
                            <span class="font-semibold flex-1 truncate" x-text="j.nome"></span>
                            <span class="text-xl font-black text-brand-orange-sand" x-text="j.gols ?? 0"></span>
                        </div>
                    </template>
                </div>

                <div class="p-4 border-t border-brand-ice/10 shrink-0">
                    <button
                        type="button"
                        @click="showArtilheiros = false"
                        class="w-full py-3 rounded-2xl bg-brand-ice/10 hover:bg-brand-ice/15 font-medium transition"
                    >
                        Fechar
                    </button>
                </div>
            </div>
        </div>
</div>

<style>[x-cloak] { display: none !important; }</style>

<script>
    function timeEdit(config) {
        return {
            timeId: config.timeId,
            updateUrl: config.updateUrl,
            nome: config.nome,
            originalNome: config.nome,
            editingNome: false,
            savingNome: false,
            previews: { logo: null, uniforme: null, estadio: null },
            existing: config.existing,
            loading: null,
            dragging: null,
            historico: [],
            artilheiros: [],
            showHistorico: false,
            showArtilheiros: false,
            loadingHistorico: false,
            loadingArtilheiros: false,

            imageUrl(field) {
                return this.previews[field] || this.existing[field] || null;
            },

            startEditNome() {
                this.editingNome = true;
                this.$nextTick(() => this.$refs.nomeInput?.focus());
            },

            cancelEditNome() {
                this.nome = this.originalNome;
                this.editingNome = false;
            },

            async saveNome() {
                const trimmed = this.nome.trim();
                if (!trimmed) {
                    this.nome = this.originalNome;
                    this.editingNome = false;
                    return;
                }

                if (trimmed === this.originalNome) {
                    this.editingNome = false;
                    return;
                }

                this.savingNome = true;

                const formData = new FormData();
                formData.append('nome', trimmed);
                formData.append('_method', 'PUT');
                formData.append('_token', @js(csrf_token()));

                try {
                    const res = await fetch(this.updateUrl, {
                        method: 'POST',
                        headers: { Accept: 'application/json' },
                        body: formData,
                    });

                    if (!res.ok) throw new Error('save failed');

                    this.nome = trimmed;
                    this.originalNome = trimmed;
                    this.editingNome = false;
                    showToast('Nome atualizado', 'success');
                } catch {
                    this.nome = this.originalNome;
                    showToast('Erro ao salvar o nome', 'error');
                } finally {
                    this.savingNome = false;
                }
            },

            handleFileSelect(event, field) {
                this.processFile(event.target.files[0], field);
                event.target.value = '';
            },

            handleDrop(event, field) {
                this.processFile(event.dataTransfer.files[0], field);
            },

            async processFile(file, field) {
                if (!file) return;

                if (file.size > 5 * 1024 * 1024) {
                    showToast('Arquivo muito grande (máx. 5 MB)', 'error');
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    showToast('Envie apenas imagens', 'error');
                    return;
                }

                this.previews[field] = URL.createObjectURL(file);
                this.loading = field;

                const formData = new FormData();
                formData.append(field, file);
                formData.append('_method', 'PUT');
                formData.append('_token', @js(csrf_token()));

                const labels = { logo: 'Escudo', uniforme: 'Uniforme', estadio: 'Estádio' };

                try {
                    const res = await fetch(this.updateUrl, {
                        method: 'POST',
                        headers: { Accept: 'application/json' },
                        body: formData,
                    });

                    if (!res.ok) throw new Error('upload failed');

                    const data = await res.json();
                    if (data.url) {
                        this.existing[field] = data.url;
                        this.previews[field] = null;
                    }

                    showToast(`${labels[field]} atualizado`, 'success');
                } catch {
                    this.previews[field] = null;
                    showToast('Erro no upload', 'error');
                } finally {
                    this.loading = null;
                }
            },

            async openHistorico() {
                this.showHistorico = true;
                this.loadingHistorico = true;

                try {
                    const res = await fetch(`/times/${this.timeId}/historico`);
                    this.historico = await res.json();
                } catch {
                    this.historico = [];
                    showToast('Erro ao carregar histórico', 'error');
                } finally {
                    this.loadingHistorico = false;
                }
            },

            async openArtilheiros() {
                this.showArtilheiros = true;
                this.loadingArtilheiros = true;

                try {
                    const res = await fetch(`/times/${this.timeId}/artilheiros`);
                    this.artilheiros = await res.json();
                } catch {
                    this.artilheiros = [];
                    showToast('Erro ao carregar artilheiros', 'error');
                } finally {
                    this.loadingArtilheiros = false;
                }
            },

            formatDate(iso) {
                try {
                    return new Date(iso).toLocaleString('pt-BR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                    });
                } catch {
                    return '—';
                }
            },

            resultadoPartida(p) {
                const casaId = p.time_casa_id;
                const isHome = casaId === this.timeId;
                const gc = Number(p.gols_casa);
                const gf = Number(p.gols_fora);

                if (gc === gf) return 'draw';
                if (isHome) return gc > gf ? 'win' : 'loss';
                return gf > gc ? 'win' : 'loss';
            },

            resultadoLabel(p) {
                const r = this.resultadoPartida(p);
                return { win: 'Vitória', loss: 'Derrota', draw: 'Empate' }[r] ?? '—';
            },
        };
    }
</script>

@endsection
