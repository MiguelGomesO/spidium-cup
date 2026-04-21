@extends('layouts.app')

@section('content')

<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">🔥 Visao Geral</h1>
        <p class="text-gray-400 text-sm">Resumo do seu sistema</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="relative group">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 blur opacity-30 group-hover:opacity-60 transition"></div>

            <div class="relative bg-[#020617] border border-white/10 rounded-xl p-6 hover:scale-105 transition">

                <div class="flex justify-between items-center">
                    <h3 class="text-gray-400 text-sm">Campeonatos</h3>
                    <span class="text-xl">🏆</span>
                </div>

                <p class="text-4xl font-bold mt-4 text-white">5</p>

                <p class="text-green-400 text-sm mt-2">
                    +2 essa semana 🚀
                </p>

            </div>
        </div>

        <div class="relative group">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-cyan-500 blur opacity-30 group-hover:opacity-60 transition"></div>

            <div class="relative bg-[#020617] border border-white/10 rounded-xl p-6 hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <h3 class="text-gray-400 text-sm">Times</h3>
                    <span class="text-xl">👥</span>
                </div>

                <p class="text-4xl font-bold mt-4 text-white">16</p>

                <p class="text-blue-400 text-sm mt-2">
                    Crescendo 📈
                </p>
            </div>
        </div>

        <div class="relative group">
            <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-orange-500 blur opacity-30 group-hover:opacity-60 transition"></div>

            <div class="relative bg-[#020617] border border-white/10 rounded-xl p-6 hover:scale-105 transition">
                <div class="flex justify-between items-center">
                    <h3 class="text-gray-400 text-sm">Jogos</h3>
                    <span class="text-xl">⚽</span>
                </div>

                <p class="text-4xl font-bold mt-4">24</p>

                <p class="text-orange-400 text-sm mt-2">
                    Em andamento 🔥
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#020617] border border-white/10 rounded-xl p-6">
            <h2 class="font-semibold mb-4">⚽ Últimos Jogos</h2>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span>Time A vs Time B</span>
                    <span class="text-green-400">2 - 1</span>
                </div>

                <div class="flex justify-between">
                    <span>Time C vs Time D</span>
                    <span class="text-red-400">0 - 3</span>
                </div>

                <div class="flex justify-between">
                    <span>Time E vs Time F</span>
                    <span class="text-yellow-400">1 - 1</span>
                </div>
            </div>

        </div>

        <div class="bg-[#020617] border border-white/10 rounded-xl p-6">
            <h2 class="font-semibold mb-4">🏆 Ranking</h2>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span>🥇 Time A</span>
                    <span>15 pontos</span>
                </div>
                <div class="flex justify-between">
                    <span>🥈 Time B</span>
                    <span>12 pontos</span>
                </div>
                <div class="flex justify-between">
                    <span>🥉 Time C</span>
                    <span>10 pontos</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
