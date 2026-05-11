<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Spidium Cup</title>
    <link rel="icon" type="image/png" href="{{ asset('images/ldpng.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#0f172a] text-white">

    <div x-data="{ open: false }" class="flex h-screen">
        <div
            x-show="open"
            @click="open = false"
            class="fixed inset-0 bg-black/50 z-40 md:hidden">
        </div>
        <aside class="fixed md:static z-[100] w-64 h-full bg-gradient-to-b from-[#1a0b2e] to-[#2b0f4a] p-5 transform transition-transform duration-300" :class="open ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
            <h1 class="text-xl font-bold mb-8">
                🛡️ Spidium Cup
            </h1>

            <nav class="space-y-3">
                <a href="#" class="block p-3 rounded-lg hover:bg-white/10 transition">
                    📊 Dashboard
                </a>

                <a href="{{ route('campeonatos.index') }}" class="block p-3 rounded-lg hover:bg-white/10 transition">
                    🏆 Campeonatos
                </a>

                <a href="{{ route('times.index') }}" class="block p-3 rounded-lg hover:bg-white/10 transition">
                    👥 Times
                </a>

                <a href="{{ route('partidas.index') }}" class="block p-3 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('partidas.*') ? 'bg-white/10' : ''}}">
                    ⚽ Partidas
                </a>

                <a href="#" class="block p-3 rounded-lg hover:bg-white/10 transition">
                    ⚙️ Configurações
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">

            <header class="bg-[#020617]/80 backdrop-blur-md p-4 flex justify-between items-center border-b border-white/10">

                <button @click="open = true" class="md:hidden text-xl">
                    ☰
                </button>

                <h2 class="font-semibold">Dashboard</h2>

                <div class="flex items-center gap-3">
                    <span>{{ Auth::user()->name }}</span>
                    <img src="{{ asset('images/ld.png') }}" class="w-10 h-10 rounded-full object-cover border border-white/20">
                </div>
            </header>

            <main class="p-6 overflow-y-auto">
                @yield('content')
            </main>

        </div>

    </div>

    <div
        x-data
        x-show="$store.toast.show"
        x-transition
        class="fixed bottom-6 right-6 z-[100]">
        <div class="bg-green-500 px-4 py-2 rounded shadow">
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
