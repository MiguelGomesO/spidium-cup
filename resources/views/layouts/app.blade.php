<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#060c30">
    <title>@yield('title', 'Spidium Cup')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/ldpng.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-black text-brand-ice h-full overflow-hidden">

    <div
        x-data="{ open: false }"
        x-effect="document.body.classList.toggle('overflow-hidden', open && window.innerWidth < 768)"
        class="flex h-full min-h-0 w-full overflow-hidden"
    >
        <div
            x-show="open"
            x-transition.opacity
            @click="open = false"
            class="fixed inset-0 bg-brand-black/80 backdrop-blur-sm z-40 md:hidden"
            aria-hidden="true"
        ></div>

        <aside
            class="fixed md:static z-[100] w-[min(100vw-3rem,18rem)] md:w-64 shrink-0 h-full bg-gradient-to-b from-brand-purple to-brand-blue flex flex-col transform transition-transform duration-300 ease-out shadow-2xl md:shadow-none"
            :class="open ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            @keydown.escape.window="open = false"
        >
            <div class="flex items-center justify-between p-4 border-b border-brand-ice/10 md:border-0 md:p-5">
                <a href="{{ route('dashboard') }}" class="font-bold text-lg" @click="open = false">
                    🛡️ Spidium Cup
                </a>
                <button
                    type="button"
                    @click="open = false"
                    class="md:hidden w-10 h-10 rounded-lg bg-brand-ice/10 flex items-center justify-center"
                    aria-label="Fechar menu"
                >
                    ✕
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto p-3 md:p-5 space-y-1">
                <a href="{{ route('dashboard') }}" @click="open = false" class="nav-drawer-link {{ request()->routeIs('dashboard') ? 'nav-drawer-link--active' : '' }}">
                    <span aria-hidden="true">📊</span> Dashboard
                </a>
                <a href="{{ route('campeonatos.index') }}" @click="open = false" class="nav-drawer-link {{ request()->routeIs('campeonatos.*') ? 'nav-drawer-link--active' : '' }}">
                    <span aria-hidden="true">🏆</span> Campeonatos
                </a>
                <a href="{{ route('times.index') }}" @click="open = false" class="nav-drawer-link {{ request()->routeIs('times.*') ? 'nav-drawer-link--active' : '' }}">
                    <span aria-hidden="true">👥</span> Times
                </a>
                <a href="{{ route('partidas.index') }}" @click="open = false" class="nav-drawer-link {{ request()->routeIs('partidas.*') ? 'nav-drawer-link--active' : '' }}">
                    <span aria-hidden="true">⚽</span> Partidas
                </a>
                <a href="{{ route('resultados.index') }}" @click="open = false" class="nav-drawer-link {{ request()->routeIs('resultados.*') ? 'nav-drawer-link--active' : '' }}">
                    <span aria-hidden="true">📺</span> Resultados
                </a>
            </nav>

            <div class="p-4 border-t border-brand-ice/10 text-xs text-brand-ice/50 hidden md:block">
                Spidium Cup Admin
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-h-0 min-w-0 overflow-hidden">
            <header class="shrink-0 bg-brand-surface/95 backdrop-blur-md px-4 py-3 sm:px-6 sm:py-4 flex items-center gap-3 border-b border-brand-ice/10 safe-area-top">
                <button
                    type="button"
                    @click="open = true"
                    class="md:hidden flex items-center justify-center w-11 h-11 rounded-xl bg-brand-ice/10 border border-brand-ice/10 shrink-0"
                    aria-label="Abrir menu"
                >
                    <span class="text-xl leading-none">☰</span>
                </button>

                <h2 class="font-semibold text-sm sm:text-base truncate flex-1 min-w-0">
                    @yield('page-title', 'Spidium Cup')
                </h2>

                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <span class="hidden sm:inline text-sm text-brand-ice/70 truncate max-w-[120px] lg:max-w-none">{{ Auth::user()->name }}</span>
                    <img src="{{ asset('images/ld.png') }}" alt="" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover border border-brand-ice/20">
                </div>
            </header>

            <main class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden overscroll-y-contain p-4 sm:p-6 pb-[calc(1rem+var(--safe-bottom))]">
                @yield('content')
            </main>
        </div>
    </div>

    <div
        x-data
        x-show="$store.toast.show"
        x-cloak
        x-transition
        class="toast-mobile"
    >
        <div class="bg-brand-asphalt border border-brand-blue-light/30 text-brand-ice px-4 py-3 rounded-xl shadow-lg text-sm text-center sm:text-left">
            <span x-text="$store.toast.message"></span>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('toast', {
                show: false,
                message: '',
                type: 'success',
                trigger(msg, type = 'success') {
                    this.message = msg;
                    this.type = type;
                    this.show = true;
                    setTimeout(() => this.show = false, 2500);
                }
            });
        });
        window.showToast = function(msg, type = 'success') {
            Alpine.store('toast').trigger(msg, type);
        };
    </script>
</body>

</html>
