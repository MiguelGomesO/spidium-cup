<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Login - Spidium Cup</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-black">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-brand-purple via-brand-blue to-brand-black px-4">

        <div class="w-full max-w-md">

            <div class="flex justify-center mb-6">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-brand-orange to-brand-purple">
                    <img src="/images/ld.png" class="rounded-full object-cover">
                </div>
            </div>

            <div class="bg-brand-ice/10 backdrop-blur-xl border border-brand-ice/10 rounded-2xl shadow-2xl p-8">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold text-brand-ice tracking-wide"> 🛡️ Spidium Cup</h1>
                    <p class="text-brand-ice/80 text-sm mt-1">Entre na sua conta</p>
                </div>

                <form method="POST" action="{{ route('login') }}" x-data="{ loading: false }" @submit="loading= true">
                    @csrf

                    <div class="mb-4">
                        <label class="text-brand-ice/80 text-sm">Email</label>
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-3 text-brand-urban">📧</span>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                class="w-full pl-10 pr-3 py-3 rounded-lg bg-brand-black/50 border border-brand-ice/10 text-brand-ice focus:ring-2 focus:ring-brand-orange focus:outline-none"
                                placeholder="Digite seu email">
                        </div>

                        @error('email')
                        <p class="text-brand-orange text-sm mt-1">
                            ⚠ {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="text-brand-ice/80 text-sm">Senha</label>

                        <div class="relative mt-1">
                            <span class="absolute left-3 top-3 text-brand-urban">🔒</span>

                            <input
                                type="password"
                                name="password"
                                required
                                class="w-full pl-10 pr-3 py-3 rounded-lg bg-brand-black/50 border border-brand-ice/10 text-brand-ice focus:ring-2 focus:ring-brand-orange focus:outline-none"
                                placeholder="Digite sua senha">
                        </div>

                        @error('password')
                        <p class="text-brand-orange text-sm mt-1">
                            ⚠ {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3 rounded-lg font-bold text-brand-ice bg-brand-gradient hover:opacity-90 transition-all flex items-center justify-center gap-2"
                        :disabled="loading">
                        <span x-show="!loading">
                            Entrar
                        </span>

                        <span x-show="loading" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-brand-ice" fill="none" viewbox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            Entrando...
                        </span>
                    </button>

                    <div class="text-right mt-4">
                        <a href="{{ route('password.request') }}" class="text-brand-orange-sand text-sm hover:underline">
                            Esqueceu a senha?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
