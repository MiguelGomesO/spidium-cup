@extends('layouts.app')

@section('content')

<script>
    function editNome(initialNome, url) {
        return {
            nome: initialNome,
            original: initialNome,
            editing: false,
            loading: false,

            startEdit() {
                this.editing = true;
                this.$nextTick(() => this.$refs.input.focus());
            },

            cancel() {
                this.nome = this.original;
                this.editing = false;
            },

            async save() {
                if (this.nome === this.original) {
                    this.editing = false;
                    return;
                }

                this.loading = true;

                let formData = new FormData();
                formData.append('nome', this.nome);
                formData.append('_method', 'PUT');
                formData.append('_token', '{{ csrf_token() }}');

                try {
                    let res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    let data = await res.json();

                    this.original = this.nome;
                    this.editing = false;

                } catch (e) {
                    alert('Erro ao salvar');
                } finally {
                    this.loading = false;
                }

            }
        }
    }

    function uploadImage(field, url) {

        return {
            preview: null,
            loading: false,
            dragging: false,

            upload(event) {
                this.processFile(event.target.files[0])
            },

            handleDrop(event) {
                let file = event.dataTransfer.files[0];
                this.processFile(file);
            },

            async processFile(file) {
                if (!file) return;

                if (file.size > 10 * 1024 * 1024) {
                    alert('Arquivo muito grande (max 10MB) ' + file.size);
                    return;
                }

                this.preview = URL.createObjectURL(file);

                this.loading = true;

                let formData = new FormData();
                formData.append(field, file);
                formData.append('_method', 'PUT');
                formData.append('_token', '{{ csrf_token() }}');

                try {
                    let res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    let data = await res.json();

                    const nomes = {
                        logo: 'Logo',
                        uniforme: 'Uniforme',
                        estadio: 'Estádio'
                    }

                    showToast(`${nomes[field]} atualizado com sucesso`, 'success');

                } catch (e) {
                    showToast('Erro no upload', 'error');
                } finally {
                    this.loading = false;
                }
            }
        }
    }

    function modais(timeId) {
        return {
            historico: [],
            artilheiros: [],
            showHistorico: false,
            showArtilheiros: false,

            async openHistorico() {
                let res = await fetch(`/times/${timeId}/historico`);
                this.historico = await res.json();
                this.showHistorico = true;
            },

            async openArtilheiros() {
                let res = await fetch(`/times/${timeId}/artilheiros`);
                this.artilheiros = await res.json();
                this.showArtilheiros = true;
            }
        }
    }
</script>

<div x-data="modais({{ $time->id }})">
    <div class="p-8 text-white space-y-8">
        <div class="flex flex-wrap items-center gap-6 bg-black/40 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl">
            <div class="w-20 h-20 rounded-xl bg-white/10 flex items-center justify-center overflow-hidden">
                @if($time->logo)
                <img src="{{ asset('storage/' . $time->logo) }}" class="object-contain w-full h-full">
                @endif
            </div>

            <div x-data="editNome('{{ $time->nome }}', '{{ route('times.update', $time->id) }}')">

                <template x-if="!editing">
                    <h1 class="text-3xl font-bold cursor-pointer transition" @click="startEdit">
                        <span x-text="nome"></span>
                    </h1>
                </template>

                <template x-if="editing">
                    <input
                        type="text"
                        x-model="nome"
                        x-ref="input"
                        @keydown.enter="save"
                        @keydown.escape="cancel"
                        @click.outside="cancel"
                        class="text-3xl font-bold bg-transparent border-b border-white/20 outline-none w-full transition-all duration-200">
                </template>

                <p x-show="loading" class="text-xs text-purple-400 mt-1 animate-pulse">
                    Salvando...
                </p>

                <p class="text-white/50 text-sm">Gerencie seu time</p>
            </div>

            <!-- <div>
                <a href="{{ route('times.escalacao', $time->id) }}" class="hidden bg-purple-600 px-4 py-2 rounded hover:bg-purple-700 transition">
                    Ver Escalação ⚽
                </a>
            </div> -->

            <div class="flex gap-3 ml-auto flex-wrap">
                <button @click="openHistorico()" class="bg-blue-600 px-4 py-2 rounded">
                    📊 Histórico
                </button>

                <button @click="openArtilheiros()" class="bg-green-600 px-4 py-2 rounded">
                    ⚽ Artilheiros
                </button>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6">
            @foreach(['logo', 'uniforme', 'estadio'] as $campo)
            <div x-data="uploadImage('{{ $campo }}', '{{ route('times.update', $time->id) }}')"
                @dragover.prevent="dragging = true"
                @dragleave="dragging = false"
                @drop.prevent="handleDrop($event); dragging = false"

                :class="dragging
                    ? 'border-purple-500 bg-purple-500/10 scale-105'
                    : 'border-white'
                "
                class="relative bg-black/40 backdrop-blur-xl border border-white/10 rounded-2xl p-6 text-center card-hover">

                <p class="text-white/60 mb-3 capitalize">{{ $campo }}</p>

                <input type="file" x-ref="input" class="hidden" @change="upload($event)">

                <div @click="$refs.input.click()" class="cursor-pointer group relative">
                    <div x-show="loading" class="absolute inset-0 bg-black/60 flex items-center justify-center rounded-lg">
                        <div class="animate-spin rounded-full h-8 w-8 border-2 border-white border-t-transparent"></div>
                    </div>

                    <template x-if="preview">
                        <img :src="preview" class="w-28 h-28 mx-auto rounded-xl object-contain border border-white/10 group-hover:scale-105 transition">
                    </template>

                    <div x-show="dragging" class="absolute inset-0 flex items-center justify-center bg-black/70 rounded-2xl z-10">
                        <span class="text-sm text-purple-300 font-semibold">
                            Solte o arquivo do {{ $campo }} aqui 📂
                        </span>
                    </div>

                    <template x-if="!preview">
                        @if($time->$campo)
                        <img src=" {{ asset('storage/' . $time->$campo) }}" class="w-28 h-28 mx-auto rounded-xl object-contain border border-white/10 group-hover:scale-105 transition">
                        @else
                        <div class="w-28 h-28 mx-auto bg-white/10 rounded-xl flex items-center justify-center">
                            ?
                        </div>

                        @endif
                    </template>
                </div>
            </div>
            @endforeach
        </div>

        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Elenco</h2>
            </div>

            <form method="POST" action="{{ route('jogadores.store', $time->id) }}" class="flex gap-2 bg-black/40 p-4 rounded-xl border border-white/10">
                @csrf

                <input name="nome" placeholder="Nome" class="flex-1 p-2 rounded bg-black/40 border border-white/10">

                <input name="posicao" placeholder="Posição" class="p-2 rounded bg-black/40 border border-white/10 w-32">

                <input name="numero" placeholder="#" class="p-2 rounded bg-black/40 border border-white/10 w-20">

                <button class="bg-purple-600 px-4 rounded hover:bg-purple-700 transition">
                    Adicionar
                </button>
            </form>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($time->jogadores as $j)
                <div class="bg-gradient-to-br from-purple-600/20 to-pink-600/20 border border-white/10 rounded-2xl p-4 backdrop-blur-xl card-hover relative">
                    <div class="text-center space-y-2">
                        <div class="text-2xl font-bold">
                            #{{ $j->numero ?? '--' }}
                        </div>

                        <div class="font-semibold">
                            {{ $j->nome }}
                        </div>

                        <div class="text-xs text-white/60 uppercase">
                            {{ $j->posicao ?? 'N/A' }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('jogadores.destroy', $j->id) }}" class="absolute top-2 right-2">
                        @csrf
                        @method('DELETE')

                        <button class="text-red-400 hover:text-red-600 text-sm">
                            ✕
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </div>



    <template x-if="showHistorico">
        <div class="fixed inset-0 flex items-center justify-center z-[9999]">
            <div @click.self="showHistorico = false" class="absolute inset-0 bg-black/60 backdrop-blur-md"></div>

            <div class="bg-[#020617] p-6 rounded-xl w-full max-w-2xl">
                <h2 class="text-xl mb-4">Histórico</h2>

                <template x-for="p in historico" :key="p.id">
                    <div class="flex justify-between border-b py-2">
                        <span x-text="new Date(p.data).toLocaleDateString()"></span>

                        <span>
                            <span x-text="p.time_casa.nome"></span>
                            <span x-text="p.gols_casa + ' x ' + p.gols_fora"></span>
                            <span x-text="p.time_fora.nome"></span>
                        </span>
                    </div>
                </template>

                <button @click="showHistorico = false" class="mt-4">Fechar</button>
            </div>
        </div>
    </template>

    <template x-if="showArtilheiros">
        <div class="fixed inset-0 flex items-center justify-center z-[9999]">
            <div @click.self="showArtilheiros = false" class="absolute inset-0 bg-black/60 backdrop-blur-md"></div>
            <div class="bg-[#020617] p-6 rounded-xl w-full max-w-2xl">
                <h2 class="text-xl mb-4">Artilheiros</h2>

                <template x-for="j in artilheiros" :key="j.id">
                    <div class="flex justify-between border-b py-2">
                        <span x-text="j.nome"></span>
                        <span x-text="j.gols"></span>
                    </div>
                </template>

                <button @click="showArtilheiros = false" class="mt-4">Fechar</button>
            </div>
        </div>
    </template>
</div>

@endsection
