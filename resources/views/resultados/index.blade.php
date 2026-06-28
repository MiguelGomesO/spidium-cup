@extends('layouts.public')

@section('title', 'Resultados')

@section('content')
    {{-- Hero --}}
    <section class="landing-hero mb-8 sm:mb-10 lg:mb-12">
        <div class="landing-hero__grid" aria-hidden="true"></div>
        <div class="landing-hero__ambient landing-hero__ambient--purple" aria-hidden="true"></div>
        <div class="landing-hero__ambient landing-hero__ambient--orange" aria-hidden="true"></div>

        <div class="landing-hero__inner">
            <div class="landing-hero__showcase">
                <div class="landing-hero__frame">
                    <div class="landing-hero__frame-glow" aria-hidden="true"></div>
                    <div class="landing-hero__frame-ring" aria-hidden="true"></div>
                    <img
                        src="{{ asset('images/throphy.png') }}"
                        alt="Troféu Spidium Cup"
                        class="landing-hero__trophy"
                        width="736"
                        height="1312"
                    >
                </div>
            </div>

            <div class="landing-hero__content">
                <div class="landing-hero__badges">
                    <span class="landing-hero__badge landing-hero__badge--live">
                        <span class="landing-hero__badge-dot"></span>
                        Temporada ativa
                    </span>
                    <span class="landing-hero__badge landing-hero__badge--org">
                        LD_Speedruns
                    </span>
                </div>

                <h1 class="landing-hero__title">
                    Bem-vindo à
                    <span class="text-brand-gradient">Spidium Cup</span>
                </h1>

                <p class="landing-hero__lead">
                    O campeonato de futebol digital da comunidade. Acompanhe placares, classificação e artilheiros — e, após cada jogo, vote no <strong class="text-brand-ice/90 font-semibold">MVP</strong> e avalie os jogadores.
                </p>

                @if ($stats['partidas'] > 0)
                    <div class="landing-hero__stats">
                        <div class="landing-hero__stat">
                            <span class="landing-hero__stat-value">{{ $stats['partidas'] }}</span>
                            <span class="landing-hero__stat-label">Jogos</span>
                        </div>
                        <div class="landing-hero__stat">
                            <span class="landing-hero__stat-value">{{ $stats['times'] }}</span>
                            <span class="landing-hero__stat-label">Times</span>
                        </div>
                        <div class="landing-hero__stat">
                            <span class="landing-hero__stat-value">{{ $stats['campeonatos'] }}</span>
                            <span class="landing-hero__stat-label">Edições</span>
                        </div>
                    </div>
                @endif

                <div class="landing-hero__actions">
                    <a href="#ultimas-partidas" class="btn-brand w-full sm:w-auto text-sm">
                        Ver resultados
                    </a>
                    <a href="#campeonatos" class="btn-ghost w-full sm:w-auto text-sm">
                        Campeonatos
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Últimas partidas --}}
    <section
        id="ultimas-partidas"
        class="mb-10 sm:mb-14"
        x-data="{ openPartidas: false }"
        x-effect="document.body.classList.toggle('overflow-hidden', openPartidas)"
        @keydown.escape.window="openPartidas = false"
    >
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-brand-ice">Últimas partidas</h2>
                <p class="mt-1 text-sm text-brand-ice/50">Os resultados mais recentes do Spidium Cup.</p>
            </div>

            @if ($todasPartidas->isNotEmpty())
                <button
                    type="button"
                    @click="openPartidas = true"
                    class="btn-ghost w-full sm:w-auto text-sm"
                >
                    Ver todas ({{ $todasPartidas->count() }})
                </button>
            @endif
        </div>

        @if ($ultimasPartidas->isEmpty())
            <div class="section-card text-center py-12">
                <div class="text-5xl mb-4">⚽</div>
                <h3 class="text-xl font-bold mb-2">Nenhuma partida finalizada ainda</h3>
                <p class="text-brand-ice/50 text-sm max-w-md mx-auto">
                    Quando os jogos forem encerrados, os placares aparecerão aqui.
                </p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($ultimasPartidas as $partida)
                    @include('resultados.partials.card-partida', ['partida' => $partida])
                @endforeach
            </div>
        @endif

        {{-- Modal: todas as partidas --}}
        <template x-teleport="body">
            <div
                x-show="openPartidas"
                x-cloak
                class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4"
                role="dialog"
                aria-modal="true"
                aria-labelledby="modal-partidas-title"
            >
                <div
                    @click="openPartidas = false"
                    class="absolute inset-0 bg-brand-black/80 backdrop-blur-md"
                    x-show="openPartidas"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                ></div>

                <div
                    class="relative w-full sm:max-w-2xl max-h-[90vh] flex flex-col bg-brand-surface border border-brand-ice/10 rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden"
                    x-show="openPartidas"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    @click.stop
                >
                    <div class="flex items-center justify-between gap-4 p-5 sm:p-6 border-b border-brand-ice/10 bg-gradient-to-r from-brand-purple/10 to-transparent shrink-0">
                        <div>
                            <h2 id="modal-partidas-title" class="text-xl font-bold">Todas as partidas</h2>
                            <p class="text-sm text-brand-ice/50 mt-0.5">{{ $todasPartidas->count() }} jogos finalizados</p>
                        </div>
                        <button
                            type="button"
                            @click="openPartidas = false"
                            class="min-h-[44px] min-w-[44px] rounded-xl bg-brand-ice/10 border border-brand-ice/10 hover:bg-brand-ice/15 transition text-lg leading-none"
                            aria-label="Fechar"
                        >
                            ✕
                        </button>
                    </div>

                    <div class="overflow-y-auto p-4 sm:p-6 space-y-3">
                        @foreach ($todasPartidas as $partida)
                            @include('resultados.partials.card-partida', ['partida' => $partida])
                        @endforeach
                    </div>
                </div>
            </div>
        </template>
    </section>

    {{-- Sobre o campeonato --}}
    <section class="mb-10 sm:mb-14 lg:mb-16">
        <div class="text-center mb-8 sm:mb-10">
            <h2 class="text-2xl sm:text-3xl font-black text-brand-ice">O que você pode fazer aqui</h2>
            <p class="mt-2 text-sm sm:text-base text-brand-ice/50 max-w-2xl mx-auto">
                Participe da torcida virtual do Spidium Cup: acompanhe o campeonato e dê sua opinião sobre quem se destacou em cada partida.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            @foreach ([
                ['icon' => 'chart', 'title' => 'Acompanhe resultados', 'desc' => 'Veja placares, classificação, chaveamento e artilheiros de cada edição do campeonato.'],
                ['icon' => 'trophy', 'title' => 'Vote no MVP', 'desc' => 'Em partidas finalizadas, escolha o melhor jogador em campo. Cada visitante pode votar uma vez por jogo.'],
                ['icon' => 'star', 'title' => 'Avalie os jogadores', 'desc' => 'Dê notas de 0 a 10 para os atletas e ajude a registrar o desempenho de cada um ao longo do torneio.'],
                ['icon' => 'ball', 'title' => 'Siga as partidas', 'desc' => 'Entre nos jogos para conferir escalações, estatísticas e tudo que rolou em cada confronto.'],
            ] as $step)
                <div class="landing-step">
                    <div class="landing-step__icon">
                        @if ($step['icon'] === 'chart')
                            <svg class="w-6 h-6 text-brand-orange-sand" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                        @elseif ($step['icon'] === 'trophy')
                            <svg class="w-6 h-6 text-brand-lilac" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.003 6.003 0 01-4.77-2.492m4.77 2.492V9.75"/></svg>
                        @elseif ($step['icon'] === 'star')
                            <svg class="w-6 h-6 text-brand-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                        @else
                            <svg class="w-6 h-6 text-brand-blue-light" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 9l1.5 3L12 9l1.5 3L15 9"/></svg>
                        @endif
                    </div>
                    <h3 class="font-bold text-brand-ice">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-xs sm:text-sm text-brand-ice/50 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Campeonatos em destaque --}}
    <section id="campeonatos" class="mb-10 sm:mb-14">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6 sm:mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-brand-ice">Edições do campeonato</h2>
                <p class="mt-1 text-sm text-brand-ice/50">As últimas edições do Spidium Cup com jogos disputados.</p>
            </div>
        </div>

        @if ($campeonatos->isEmpty())
            <div class="championship-card items-center text-center py-12">
                <div class="text-5xl mb-4">🏆</div>
                <h3 class="text-xl font-bold mb-2">Nenhum campeonato ainda</h3>
                <p class="text-brand-ice/50 text-sm">Os resultados aparecerão aqui quando houver campeonatos com jogos disputados.</p>
            </div>
        @else
            <div class="grid-cards">
                @foreach ($campeonatos as $index => $campeonato)
                    @include('resultados.partials.card-campeonato', [
                        'campeonato' => $campeonato,
                        'index' => $index,
                    ])
                @endforeach
            </div>
        @endif
    </section>

    {{-- Números do campeonato --}}
    <section class="rounded-2xl sm:rounded-3xl border border-brand-ice/10 bg-brand-surface/60 p-6 sm:p-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @foreach ([
                ['key' => 'times', 'label' => 'Times cadastrados', 'color' => 'text-brand-orange'],
                ['key' => 'partidas', 'label' => 'Jogos disputados', 'color' => 'text-brand-lilac'],
                ['key' => 'jogadores', 'label' => 'Jogadores ativos', 'color' => 'text-brand-blue-light'],
                ['key' => 'campeonatos', 'label' => 'Campeonatos realizados', 'color' => 'text-brand-asphalt'],
            ] as $stat)
                <div class="platform-stat">
                    <div class="platform-stat__icon">
                        @if ($stat['key'] === 'times')
                            <svg class="w-5 h-5 {{ $stat['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                        @elseif ($stat['key'] === 'partidas')
                            <svg class="w-5 h-5 {{ $stat['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 01-.657.643 48.39 48.39 0 01-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 01-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 00-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 01-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 00.657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 01-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 005.427-.63 48.05 48.05 0 00.582-4.717.532.532 0 00-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 00.658-.663 48.422 48.422 0 00-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 01-.61-.58v0z"/></svg>
                        @elseif ($stat['key'] === 'jogadores')
                            <svg class="w-5 h-5 {{ $stat['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        @else
                            <svg class="w-5 h-5 {{ $stat['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.003 6.003 0 01-4.77-2.492m4.77 2.492V9.75"/></svg>
                            <svg class="w-6 h-6 text-brand-blue-light" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 9l1.5 3L12 9l1.5 3L15 9"/></svg>
                        @endif
                    </div>
                    <p class="platform-stat__value">{{ $stats[$stat['key']] }}{{ $stats[$stat['key']] > 0 ? '+' : '' }}</p>
                    <p class="text-[10px] sm:text-xs text-brand-ice/50">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
@endsection
