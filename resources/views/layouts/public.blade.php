<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Resultados') — Spidium Cup</title>
    <link rel="icon" type="image/png" href="{{ asset('images/ldpng.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-black text-brand-ice min-h-screen">
    <header class="border-b border-brand-ice/10 bg-brand-surface/90 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
            <a href="{{ route('resultados.index') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/ld.png') }}" alt="Spidium Cup" class="w-10 h-10 rounded-full object-cover border border-brand-ice/20">
                <div>
                    <p class="font-bold text-lg leading-tight group-hover:text-brand-orange-sand transition">Spidium Cup</p>
                    <p class="text-xs text-brand-ice/50">Resultados ao vivo</p>
                </div>
            </a>

            @auth
                <a href="{{ route('dashboard') }}" class="text-sm px-4 py-2 rounded-xl bg-brand-ice/10 hover:bg-brand-ice/15 border border-brand-ice/10 transition">
                    Painel admin
                </a>
            @else
                <a href="{{ route('login') }}" class="text-sm px-4 py-2 rounded-xl bg-brand-gradient hover:opacity-90 transition">
                    Entrar
                </a>
            @endauth
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        @yield('content')
    </main>

    <footer class="border-t border-brand-ice/10 mt-12 py-6 text-center text-sm text-brand-urban">
        Spidium Cup — apenas visualização de resultados
    </footer>
</body>

</html>
