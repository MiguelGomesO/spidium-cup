@extends('layouts.app')

@section('content')

<div
    x-data="{
        nome: @js(old('nome', '')),
        logoPreview: null,
        uniformePreview: null,
        estadioPreview: null,

        previewFile(event, field) {
            const file = event.target.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            if (field === 'logo') this.logoPreview = url;
            if (field === 'uniforme') this.uniformePreview = url;
            if (field === 'estadio') this.estadioPreview = url;
        },

        clearPreview(field) {
            if (field === 'logo') this.logoPreview = null;
            if (field === 'uniforme') this.uniformePreview = null;
            if (field === 'estadio') this.estadioPreview = null;
        },
    }"
    class="max-w-5xl mx-auto space-y-8"
>
    <a
        href="{{ route('times.index') }}"
        class="inline-flex items-center gap-2 text-sm text-brand-ice/60 hover:text-brand-ice transition"
    >
        <span aria-hidden="true">←</span>
        Voltar aos times
    </a>

    <div class="relative overflow-hidden rounded-3xl border border-brand-ice/10 bg-brand-surface p-8 lg:p-10">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-blue/15 via-brand-purple/10 to-brand-orange/10"></div>
        <div class="absolute -top-16 -right-16 w-56 h-56 bg-brand-blue/20 blur-3xl rounded-full"></div>
        <div class="absolute -bottom-16 -left-16 w-56 h-56 bg-brand-orange/15 blur-3xl rounded-full"></div>

        <div class="relative z-10">
            <span class="inline-block px-3 py-1 rounded-full bg-brand-ice/10 border border-brand-ice/10 text-xs text-brand-ice/60 mb-4">
                Novo time
            </span>
            <h1 class="text-3xl lg:text-4xl font-black text-brand-gradient">
                Cadastre seu time
            </h1>
            <p class="mt-3 text-brand-ice/60 max-w-xl leading-relaxed">
                Defina o nome e envie o escudo. Uniforme e estádio são opcionais — você pode ajustar tudo depois.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
        <div class="lg:col-span-3 space-y-6">
            <form
                method="POST"
                action="{{ route('times.store') }}"
                enctype="multipart/form-data"
                class="space-y-6"
            >
                @csrf

                <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6 lg:p-8 space-y-5">
                    <div>
                        <h2 class="text-lg font-bold text-brand-ice">1. Nome do time</h2>
                        <p class="text-sm text-brand-ice/50 mt-1">Obrigatório. Aparece em partidas e classificações.</p>
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
                            placeholder="Ex: Spidium FC"
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
                        <h2 class="text-lg font-bold text-brand-ice">2. Escudo</h2>
                        <p class="text-sm text-brand-ice/50 mt-1">PNG ou JPG, até 5 MB. Recomendado fundo transparente.</p>
                    </div>

                    <label
                        class="relative flex flex-col items-center justify-center gap-4 p-8 rounded-2xl border-2 border-dashed border-brand-ice/15 bg-brand-black/30 hover:border-brand-blue-light/40 hover:bg-brand-blue/10 transition cursor-pointer"
                    >
                        <template x-if="logoPreview">
                            <img :src="logoPreview" alt="Prévia do escudo" class="w-28 h-28 object-contain">
                        </template>
                        <template x-if="!logoPreview">
                            <div class="w-28 h-28 rounded-2xl bg-brand-ice/5 border border-brand-ice/10 flex items-center justify-center text-4xl">
                                🛡️
                            </div>
                        </template>

                        <div class="text-center">
                            <span class="text-sm font-medium text-brand-blue-light">Clique para escolher o escudo</span>
                            <span class="block text-xs text-brand-urban mt-1">ou arraste a imagem aqui</span>
                        </div>

                        <input
                            type="file"
                            name="logo"
                            accept="image/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            @change="previewFile($event, 'logo')"
                        >
                    </label>

                    @error('logo')
                        <p class="text-sm text-brand-orange">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-6 lg:p-8 space-y-5">
                    <div>
                        <h2 class="text-lg font-bold text-brand-ice">3. Imagens extras</h2>
                        <p class="text-sm text-brand-ice/50 mt-1">Opcional. Deixe em branco se quiser configurar depois.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <span class="text-sm font-medium text-brand-ice/80">Uniforme</span>
                            <label class="relative block p-5 rounded-2xl border border-brand-ice/10 bg-brand-black/30 hover:border-brand-lilac/40 transition cursor-pointer text-center min-h-[140px] flex flex-col items-center justify-center gap-2">
                                <template x-if="uniformePreview">
                                    <img :src="uniformePreview" alt="Prévia uniforme" class="max-h-20 object-contain">
                                </template>
                                <template x-if="!uniformePreview">
                                    <span class="text-2xl" aria-hidden="true">👕</span>
                                    <span class="text-xs text-brand-urban">Enviar uniforme</span>
                                </template>
                                <input
                                    type="file"
                                    name="uniforme"
                                    accept="image/*"
                                    class="absolute inset-0 opacity-0 cursor-pointer"
                                    @change="previewFile($event, 'uniforme')"
                                >
                            </label>
                            @error('uniforme')
                                <p class="text-xs text-brand-orange">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <span class="text-sm font-medium text-brand-ice/80">Estádio</span>
                            <label class="relative block p-5 rounded-2xl border border-brand-ice/10 bg-brand-black/30 hover:border-brand-lilac/40 transition cursor-pointer text-center min-h-[140px] flex flex-col items-center justify-center gap-2">
                                <template x-if="estadioPreview">
                                    <img :src="estadioPreview" alt="Prévia estádio" class="max-h-20 object-contain rounded-lg">
                                </template>
                                <template x-if="!estadioPreview">
                                    <span class="text-2xl" aria-hidden="true">🏟️</span>
                                    <span class="text-xs text-brand-urban">Enviar estádio</span>
                                </template>
                                <input
                                    type="file"
                                    name="estadio"
                                    accept="image/*"
                                    class="absolute inset-0 opacity-0 cursor-pointer"
                                    @change="previewFile($event, 'estadio')"
                                >
                            </label>
                            @error('estadio')
                                <p class="text-xs text-brand-orange">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button
                        type="submit"
                        :disabled="!nome.trim()"
                        :class="!nome.trim() ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90'"
                        class="flex-1 py-4 rounded-2xl font-bold bg-brand-gradient shadow-lg shadow-brand-purple/20 transition"
                    >
                        Criar time
                    </button>

                    <a
                        href="{{ route('times.index') }}"
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
                    <p class="text-xs text-brand-ice/50 mt-1">Como o card aparecerá na lista</p>
                </div>

                <div class="p-6 rounded-2xl bg-brand-black/40 border border-brand-ice/5">
                    <div class="flex items-center gap-4">
                        <template x-if="logoPreview">
                            <img :src="logoPreview" alt="" class="w-16 h-16 object-contain rounded-2xl bg-brand-black/50 border border-brand-ice/10 p-1">
                        </template>
                        <template x-if="!logoPreview">
                            <div class="w-16 h-16 rounded-2xl bg-brand-black/50 border border-brand-ice/10 flex items-center justify-center text-2xl">
                                ⚽
                            </div>
                        </template>

                        <div class="min-w-0">
                            <p class="font-bold text-lg text-brand-ice truncate" x-text="nome.trim() || 'Nome do time'"></p>
                            <p class="text-xs text-brand-urban mt-1">0 jogadores · 0 campeonatos</p>
                        </div>
                    </div>
                </div>

                <ul class="text-xs text-brand-ice/50 space-y-2 border-t border-brand-ice/10 pt-4">
                    <li class="flex gap-2">
                        <span class="text-brand-blue-light">✓</span>
                        Após criar, você será levado à página do time para adicionar jogadores.
                    </li>
                    <li class="flex gap-2">
                        <span class="text-brand-blue-light">✓</span>
                        Inscreva o time nos campeonatos pela edição de cada torneio.
                    </li>
                </ul>
            </div>
        </aside>
    </div>
</div>

@endsection
