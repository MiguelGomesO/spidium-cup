<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#060C30">
    <title>@yield('title', 'Spidium Cup')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/ldpng.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-black text-brand-ice h-full overflow-hidden">

    <div
        x-data="{
            open: false,
            sidebarVisible: true,
            init() {
                try {
                    this.sidebarVisible = localStorage.getItem('spidium_sidebar_hidden') !== '1';
                } catch (e) {
                    this.sidebarVisible = true;
                }
            },
            toggleSidebar() {
                this.sidebarVisible = !this.sidebarVisible;
                try {
                    localStorage.setItem('spidium_sidebar_hidden', this.sidebarVisible ? '0' : '1');
                } catch (e) {}
            },
            toggleNav() {
                if (window.matchMedia('(min-width: 768px)').matches) {
                    this.toggleSidebar();
                } else {
                    this.open = !this.open;
                }
            },
            navOpen() {
                return window.matchMedia('(min-width: 768px)').matches
                    ? this.sidebarVisible
                    : this.open;
            },
            closeNav() {
                if (window.matchMedia('(min-width: 768px)').matches) {
                    this.sidebarVisible = false;
                    try {
                        localStorage.setItem('spidium_sidebar_hidden', '1');
                    } catch (e) {}
                } else {
                    this.open = false;
                }
            },
        }"
        x-effect="document.body.classList.toggle('overflow-hidden', open && window.innerWidth < 768)"
        class="flex h-full min-h-0 w-full overflow-hidden"
    >
        <div
            x-show="open"
            x-transition.opacity
            @click="open = false"
            class="fixed inset-0 bg-brand-black/80 backdrop-blur-sm z-40 md:hidden"
            style="display: none;"
            aria-hidden="true"
        ></div>

        <aside
            class="app-sidebar shrink-0 h-full max-md:fixed max-md:z-[100] max-md:inset-y-0 max-md:left-0 max-md:w-[min(100vw-3rem,18rem)] md:relative overflow-hidden transition-all duration-300 ease-in-out max-md:transition-transform"
            :class="[
                open ? 'max-md:translate-x-0 max-md:pointer-events-auto' : 'max-md:-translate-x-full max-md:pointer-events-none',
                sidebarVisible ? 'md:w-64' : 'md:w-14',
            ]"
            @keydown.escape.window="closeNav()"
        >
            <div class="app-sidebar__inner h-full flex flex-col bg-gradient-to-b from-brand-purple to-brand-blue max-md:shadow-2xl max-md:w-64 md:min-h-0">
                <div
                    class="app-sidebar__head flex items-center shrink-0 border-b border-brand-ice/10"
                    :class="sidebarVisible ? 'gap-2 p-4 md:p-5' : 'justify-center p-3 md:p-2'"
                >
                    <a
                        href="{{ route('dashboard') }}"
                        x-show="sidebarVisible || open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="font-bold text-lg truncate flex-1 min-w-0"
                    >
                        🛡️ Spidium Cup
                    </a>

                    <button
                        type="button"
                        @click="toggleNav()"
                        class="sidebar-toggle-btn shrink-0"
                        :aria-label="navOpen() ? 'Ocultar menu' : 'Mostrar menu'"
                        :aria-expanded="navOpen()"
                    >
                        <svg class="sidebar-toggle-btn__icon max-md:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="sidebarVisible ? 'M15 19l-7-7 7-7' : 'M9 5l7 7-7 7'" />
                        </svg>
                        <svg class="sidebar-toggle-btn__icon md:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            <nav
                x-show="sidebarVisible || open"
                x-transition:enter="transition ease-out duration-200 delay-75"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="flex-1 overflow-y-auto p-3 md:p-5 space-y-1"
            >
                <a href="{{ route('dashboard') }}" class="nav-drawer-link {{ request()->routeIs('dashboard') ? 'nav-drawer-link--active' : '' }}">
                    <span aria-hidden="true">📊</span> Dashboard
                </a>
                <a href="{{ route('campeonatos.index') }}" class="nav-drawer-link {{ request()->routeIs('campeonatos.*') ? 'nav-drawer-link--active' : '' }}">
                    <span aria-hidden="true">🏆</span> Campeonatos
                </a>
                <a href="{{ route('times.index') }}" class="nav-drawer-link {{ request()->routeIs('times.*') ? 'nav-drawer-link--active' : '' }}">
                    <span aria-hidden="true">👥</span> Times
                </a>
                <a href="{{ route('partidas.index') }}" class="nav-drawer-link {{ request()->routeIs('partidas.*') ? 'nav-drawer-link--active' : '' }}">
                    <span aria-hidden="true">⚽</span> Partidas
                </a>
                <a href="{{ route('resultados.index') }}" class="nav-drawer-link {{ request()->routeIs('resultados.*') ? 'nav-drawer-link--active' : '' }}">
                    <span aria-hidden="true">📺</span> Resultados
                </a>
            </nav>

            <div
                x-show="sidebarVisible || open"
                x-transition:enter="transition ease-out duration-200 delay-75"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="p-4 border-t border-brand-ice/10 text-xs text-brand-ice/50"
            >
                Spidium Cup Admin
            </div>
            </div>
        </aside>

        <div class="relative z-10 flex-1 flex flex-col min-h-0 min-w-0 overflow-hidden">
            <header class="shrink-0 bg-brand-surface/95 backdrop-blur-md px-4 py-3 sm:px-6 sm:py-4 flex items-center gap-3 border-b border-brand-ice/10 safe-area-top">
                <button
                    type="button"
                    @click="open = true"
                    x-show="!open"
                    class="sidebar-toggle-btn shrink-0 md:hidden"
                    aria-label="Abrir menu"
                >
                    <svg class="sidebar-toggle-btn__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
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

            <x-site-footer />
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
