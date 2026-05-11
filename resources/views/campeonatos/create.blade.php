@extends("layouts.app")

@section("content")

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#020617] via-[#0f172a] to-[#020617]">
    <div x-data="{
            formato: 'liga',
            qtd_times: 2,

            get grupos() {
                if (this.formato !== 'grupos') return 0;

                return Math.max(2, Math.floor(this.qtd_times / 4));
            },

            get erro() {
                if (this.formato === 'liga' && this.qtd_times < 2) {
                    return 'Liga precisa de pelo menos 2 times.';
                }

                if (this.formato === 'grupos' && this.qtd_times < 8) {
                    return 'Grupos precisam de no mínimo 8 times.';
                }

                if (this.formato === 'mata_mata' && this.qtd_times % 2 !== 0) {
                    return 'Mata-mata precisa de número par de times.';
                }

                return '';
            }
        }"
        class="w-full max-w-md bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl"
    >

        <h1 class="text-2xl font-bold mb-6 text-center bg-gradient-to-r from-orange-400 via-purple-500 to-blue-500 bg-clip-text text-transparent">
            Novo Campeonato
        </h1>

        <form method="POST" action="{{ route('campeonatos.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm text-white/60">Nome</label>
                <input name="nome" required class="w-full mt-1 p-3 rounded-xl bg-black/40 border border-white/10 focus:border-orange-400 focus:ring-2 focus:ring-orange-400/30 outline-none transition" autocomplete="off">
            </div>

            <div>
                <label class="text-sm text-white/60">Formato</label>
                <select name="formato" x-model="formato" class="w-full mt-1 p-3 rounded-xl bg-black/40 border border-white/10 focus:border-purple-400 focus:ring-2 focus:ring-purple-400/30 outline-none transition">
                    <option value="liga">Liga (Pontos Corridos)</option>
                    <option value="grupos">Grupos (Copa)</option>
                    <option value="mata_mata">Mata-Mata</option>
                </select>
            </div>

            <div>
                <label class="text-sm text-white/60">Times</label>
                <input name="qtd_times" type="number" x-model="qtd_times" class="w-full mt-1 p-3 rounded-xl bg-black/40 border border-white/10 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/30 outline-none transition" min="2">
            </div>

            <div x-show="erro" class="bg-red-500/10 border border-red-500/20 text-red-300 text-sm rounded-xl p-3">
                <span x-text="erro"></span>
            </div>

            <button
                :disabled="erro.length > 0"
                :class="erro ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90 cursor-pointer'"
                class="w-full py-3 rounded-xl font-semibold bg-gradient-to-r from-orange-500 via-purple-500 to-blue-500 hover:opacity-90 transition shadow-lg" type="submit">
                Criar Campeonato
            </button>
        </form>
    </div>
</div>
@endsection
