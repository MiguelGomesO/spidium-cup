@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div>
            <span class="inline-block px-3 py-1 rounded-full bg-brand-ice/10 border border-brand-ice/10 text-xs text-brand-ice/60 mb-3">
                Elenco
            </span>
            <h1 class="text-3xl lg:text-4xl font-black text-brand-gradient">
                Times
            </h1>
            <p class="text-brand-ice/60 mt-2 max-w-lg">
                Gerencie escudos, jogadores e inscrições nos campeonatos.
            </p>
        </div>

        <a
            href="{{ route('times.create') }}"
            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-brand-gradient font-semibold hover:opacity-90 transition shadow-lg shadow-brand-purple/20 shrink-0"
        >
            <span class="text-lg leading-none" aria-hidden="true">+</span>
            Novo time
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-brand-surface border border-brand-ice/10 rounded-2xl p-5">
            <p class="text-sm text-brand-urban">Total de times</p>
            <p class="text-3xl font-black mt-1 text-brand-ice">{{ $times->count() }}</p>
        </div>
        <div class="bg-brand-surface border border-brand-ice/10 rounded-2xl p-5">
            <p class="text-sm text-brand-urban">Jogadores</p>
            <p class="text-3xl font-black mt-1 text-brand-blue-light">{{ $times->sum('jogadores_count') }}</p>
        </div>
        <div class="bg-brand-surface border border-brand-ice/10 rounded-2xl p-5 col-span-2 lg:col-span-2">
            <p class="text-sm text-brand-urban">Dica</p>
            <p class="text-sm text-brand-ice/60 mt-2 leading-relaxed">
                Clique em um time para editar elenco, escalação e histórico de partidas.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-brand-asphalt/50 border border-brand-blue-light/25 text-brand-blue-light text-sm rounded-2xl px-5 py-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($times->isEmpty())
        <div class="bg-brand-surface border border-brand-ice/10 rounded-3xl p-12 lg:p-16 text-center">
            <div class="text-6xl mb-6" aria-hidden="true">👥</div>
            <h2 class="text-2xl font-bold text-brand-ice mb-2">Nenhum time cadastrado</h2>
            <p class="text-brand-ice/50 max-w-md mx-auto mb-8">
                Crie o primeiro time para começar a montar campeonatos e registrar jogadores.
            </p>
            <a
                href="{{ route('times.create') }}"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-brand-gradient font-semibold hover:opacity-90 transition"
            >
                Cadastrar primeiro time
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach ($times as $time)
                <article class="group relative bg-brand-surface border border-brand-ice/10 hover:border-brand-lilac/40 rounded-3xl overflow-hidden transition hover:-translate-y-0.5">
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-purple/10 via-transparent to-brand-orange/5 opacity-0 group-hover:opacity-100 transition"></div>

                    <div class="relative p-6">
                        <div class="flex items-start gap-4">
                            <div class="relative shrink-0">
                                @if ($time->logo)
                                    <img
                                        src="{{ asset('storage/' . $time->logo) }}"
                                        alt="Escudo {{ $time->nome }}"
                                        class="w-16 h-16 object-contain rounded-2xl bg-brand-black/40 border border-brand-ice/10 p-1"
                                    >
                                @else
                                    <div class="w-16 h-16 rounded-2xl bg-brand-black/50 border border-brand-ice/10 flex items-center justify-center text-2xl">
                                        ⚽
                                    </div>
                                @endif

                                @if ($time->cor)
                                    <span
                                        class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-brand-surface"
                                        style="background-color: {{ $time->cor }}"
                                        title="Cor do time"
                                    ></span>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1 pt-1">
                                <h2 class="text-lg font-bold text-brand-ice truncate group-hover:text-brand-orange-sand transition">
                                    {{ $time->nome }}
                                </h2>

                                <div class="flex flex-wrap gap-2 mt-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-brand-blue/25 text-xs text-brand-blue-light">
                                        {{ $time->jogadores_count }} {{ $time->jogadores_count === 1 ? 'jogador' : 'jogadores' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-brand-purple/25 text-xs text-brand-lilac">
                                        {{ $time->campeonatos_count }} {{ $time->campeonatos_count === 1 ? 'campeonato' : 'campeonatos' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2 mt-6 pt-5 border-t border-brand-ice/10">
                            <a
                                href="{{ route('times.edit', $time) }}"
                                class="flex-1 text-center py-2.5 rounded-xl bg-brand-ice/10 border border-brand-ice/10 text-sm font-medium text-brand-ice hover:bg-brand-blue/20 hover:border-brand-blue-light/30 hover:text-brand-blue-light transition"
                            >
                                Gerenciar
                            </a>

                            <form
                                method="POST"
                                action="{{ route('times.destroy', $time) }}"
                                class="shrink-0"
                                onsubmit="return confirm('Excluir o time {{ $time->nome }}? Esta ação não pode ser desfeita.')"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="h-full px-4 py-2.5 rounded-xl bg-brand-orange/10 border border-brand-orange/20 text-sm text-brand-orange-sand hover:bg-brand-orange/20 transition"
                                    title="Excluir time"
                                >
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>

@endsection
