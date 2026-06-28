<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#060C30">
    <title>@yield('title', 'Resultados') — Spidium Cup</title>
    <link rel="icon" type="image/png" href="{{ asset('images/ldpng.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-black text-brand-ice min-h-screen flex flex-col">
    <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -top-40 -right-20 h-80 w-80 rounded-full bg-brand-orange/5 blur-3xl"></div>
        <div class="absolute top-1/3 -left-32 h-96 w-96 rounded-full bg-brand-purple/8 blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 h-64 w-64 rounded-full bg-brand-blue/5 blur-3xl"></div>
    </div>
    <header class="relative z-50 border-b border-brand-ice/10 bg-brand-surface/95 backdrop-blur-md sticky top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-3">
            <a href="{{ route('resultados.index') }}" class="flex items-center gap-2 sm:gap-3 group min-w-0">
                <img src="{{ asset('images/ld.png') }}" alt="Spidium Cup" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover border border-brand-ice/20 shrink-0">
                <div class="min-w-0">
                    <p class="font-bold text-base sm:text-lg leading-tight truncate group-hover:text-brand-orange-sand transition">Spidium Cup</p>
                    <p class="text-[10px] sm:text-xs text-brand-ice/50">Resultados ao vivo</p>
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm min-h-[44px] inline-flex items-center px-3 sm:px-4 rounded-xl bg-brand-ice/10 hover:bg-brand-ice/15 border border-brand-ice/10 transition">
                        <span class="hidden sm:inline">Painel </span>admin
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="relative z-10 flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 py-5 sm:py-8 pb-[calc(1.5rem+var(--safe-bottom))]">
        @yield('content')
    </main>

    <x-site-footer />
</body>

</html>
