<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Spidium Cup</title>
    <link rel="icon" type="image/png" href="{{ asset('images/ldpng.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-black text-brand-ice h-full overflow-hidden">

    <div x-data="{ open: false }" class="flex h-full min-h-0 w-full overflow-hidden">
        <div
            x-show="open"
            @click="open = false"
            class="fixed inset-0 bg-brand-black/70 z-40 md:hidden">
        </div>
        <aside class="fixed md:static z-[100] w-64 shrink-0 h-full bg-gradient-to-b from-brand-purple to-brand-blue p-5 transform transition-transform duration-300" :class="open ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
            <h1 class="text-xl font-bold mb-8">
                🛡️ Spidium Cup
            </h1>

            <nav class="space-y-3">
                <a href="{{ route('dashboard') }}" class="block p-3 rounded-lg hover:bg-brand-ice/10 transition {{ request()->routeIs('dashboard') ? 'bg-brand-ice/10' : ''}}">
                    📊 Dashboard
                </a>

                <a href="{{ route('campeonatos.index') }}" class="block p-3 rounded-lg hover:bg-brand-ice/10 transition">
                    🏆 Campeonatos
                </a>

                <a href="{{ route('times.index') }}" class="block p-3 rounded-lg hover:bg-brand-ice/10 transition">
                    👥 Times
                </a>

                <a href="{{ route('partidas.index') }}" class="block p-3 rounded-lg hover:bg-brand-ice/10 transition {{ request()->routeIs('partidas.*') ? 'bg-brand-ice/10' : ''}}">
                    ⚽ Partidas
                </a>

                <a href="{{ route('resultados.index') }}" class="block p-3 rounded-lg hover:bg-brand-ice/10 transition {{ request()->routeIs('resultados.*') ? 'bg-brand-ice/10' : ''}}">
                    📺 Resultados públicos
                </a>

                <a href="#" class="block p-3 rounded-lg hover:bg-brand-ice/10 transition">
                    ⚙️ Configurações
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col min-h-0 min-w-0 overflow-hidden">

            <header class="shrink-0 bg-brand-surface/90 backdrop-blur-md p-4 flex justify-between items-center border-b border-brand-ice/10">

                <button @click="open = true" class="md:hidden text-xl">
                    ☰
                </button>

                <h2 class="font-semibold">Dashboard</h2>

                <div class="flex items-center gap-3">
                    <span>{{ Auth::user()->name }}</span>
                    <img src="{{ asset('images/ld.png') }}" class="w-10 h-10 rounded-full object-cover border border-brand-ice/20">
                </div>
            </header>

            <main class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden p-6">
                @yield('content')
            </main>

        </div>

    </div>

    <div
        x-data
        x-show="$store.toast.show"
        x-cloak
        x-transition
        class="fixed bottom-6 right-6 z-[100] pointer-events-none">
        <div class="bg-brand-asphalt border border-brand-blue-light/30 text-brand-ice px-4 py-2 rounded shadow">
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

        window.showToast = function(msg, type = "success") {
            Alpine.store('toast').trigger(msg, type)
        }
    </script>
</body>

</html>
