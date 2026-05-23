@props([
    'timesForSelect',
    'casa' => null,
    'fora' => null,
    'readonlyTeams' => false,
])

<div
    x-data="{
        openCasa: false,
        openFora: false,
        casa: @js($casa),
        fora: @js($fora),
        times: @js($timesForSelect),
        readonlyTeams: @js($readonlyTeams),

        selectCasa(time) {
            if (this.readonlyTeams) return;
            this.casa = time;
            if (this.fora && this.fora.id === time.id) this.fora = null;
            this.openCasa = false;
        },

        selectFora(time) {
            if (this.readonlyTeams) return;
            this.fora = time;
            if (this.casa && this.casa.id === time.id) this.casa = null;
            this.openFora = false;
        },

        timesDisponiveis(excluir) {
            if (!excluir) return this.times;
            return this.times.filter(t => t.id !== excluir.id);
        },
    }"
    class="space-y-6"
>
    <div class="flex flex-col lg:flex-row items-stretch gap-4 lg:gap-6">
        <div class="flex-1 relative">
            <label class="text-sm font-medium text-brand-ice/80 mb-2 block">Time da casa</label>

            <div
                @click="!readonlyTeams && (openCasa = !openCasa)"
                :class="readonlyTeams ? 'opacity-70 cursor-default' : 'cursor-pointer hover:border-brand-blue-light/40'"
                class="bg-brand-black/40 border border-brand-ice/10 rounded-2xl p-4 flex items-center justify-between transition"
            >
                <template x-if="casa">
                    <div class="flex items-center gap-3 min-w-0">
                        <img x-show="casa.logo" :src="casa.logo" class="w-10 h-10 object-contain shrink-0" alt="">
                        <span x-show="!casa.logo" class="text-xl shrink-0">⚽</span>
                        <span class="font-semibold truncate" x-text="casa.nome"></span>
                    </div>
                </template>
                <template x-if="!casa">
                    <span class="text-brand-urban">Selecione o mandante</span>
                </template>
                <span x-show="!readonlyTeams" class="text-brand-urban text-sm">▼</span>
            </div>

            <div
                x-show="openCasa && !readonlyTeams"
                x-transition
                @click.outside="openCasa = false"
                class="absolute z-50 w-full mt-2 rounded-2xl bg-brand-surface border border-brand-ice/10 shadow-2xl max-h-60 overflow-auto"
            >
                <template x-for="time in timesDisponiveis(fora)" :key="time.id">
                    <button
                        type="button"
                        @click="selectCasa(time)"
                        class="w-full p-3 hover:bg-brand-ice/10 flex items-center gap-3 text-left transition"
                    >
                        <img x-show="time.logo" :src="time.logo" class="w-8 h-8 object-contain" alt="">
                        <span x-show="!time.logo" class="text-lg">⚽</span>
                        <span x-text="time.nome"></span>
                    </button>
                </template>
                <template x-if="casa">
                    <button type="button" @click="casa = null; openCasa = false" class="w-full p-3 text-brand-orange text-sm hover:bg-brand-orange/10 border-t border-brand-ice/10">
                        Limpar seleção
                    </button>
                </template>
            </div>

            <input type="hidden" name="time_casa_id" :value="casa?.id">
            @error('time_casa_id')
                <p class="mt-2 text-sm text-brand-orange">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-center lg:pt-8">
            <span class="text-2xl font-black text-brand-urban">VS</span>
        </div>

        <div class="flex-1 relative">
            <label class="text-sm font-medium text-brand-ice/80 mb-2 block">Time visitante</label>

            <div
                @click="!readonlyTeams && (openFora = !openFora)"
                :class="readonlyTeams ? 'opacity-70 cursor-default' : 'cursor-pointer hover:border-brand-blue-light/40'"
                class="bg-brand-black/40 border border-brand-ice/10 rounded-2xl p-4 flex items-center justify-between transition"
            >
                <template x-if="fora">
                    <div class="flex items-center gap-3 min-w-0">
                        <img x-show="fora.logo" :src="fora.logo" class="w-10 h-10 object-contain shrink-0" alt="">
                        <span x-show="!fora.logo" class="text-xl shrink-0">⚽</span>
                        <span class="font-semibold truncate" x-text="fora.nome"></span>
                    </div>
                </template>
                <template x-if="!fora">
                    <span class="text-brand-urban">Selecione o visitante</span>
                </template>
                <span x-show="!readonlyTeams" class="text-brand-urban text-sm">▼</span>
            </div>

            <div
                x-show="openFora && !readonlyTeams"
                x-transition
                @click.outside="openFora = false"
                class="absolute z-50 w-full mt-2 rounded-2xl bg-brand-surface border border-brand-ice/10 shadow-2xl max-h-60 overflow-auto"
            >
                <template x-for="time in timesDisponiveis(casa)" :key="time.id">
                    <button
                        type="button"
                        @click="selectFora(time)"
                        class="w-full p-3 hover:bg-brand-ice/10 flex items-center gap-3 text-left transition"
                    >
                        <img x-show="time.logo" :src="time.logo" class="w-8 h-8 object-contain" alt="">
                        <span x-show="!time.logo" class="text-lg">⚽</span>
                        <span x-text="time.nome"></span>
                    </button>
                </template>
                <template x-if="fora">
                    <button type="button" @click="fora = null; openFora = false" class="w-full p-3 text-brand-orange text-sm hover:bg-brand-orange/10 border-t border-brand-ice/10">
                        Limpar seleção
                    </button>
                </template>
            </div>

            <input type="hidden" name="time_fora_id" :value="fora?.id">
            @error('time_fora_id')
                <p class="mt-2 text-sm text-brand-orange">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <template x-if="casa || fora">
        <div class="rounded-2xl border border-brand-ice/10 bg-brand-black/40 p-6">
            <p class="text-xs text-brand-urban uppercase tracking-wider mb-4 text-center">Prévia do confronto</p>
            <div class="flex items-center justify-between gap-4">
                <div class="flex-1 flex flex-col items-center gap-2 text-center min-w-0">
                    <template x-if="casa">
                        <img x-show="casa.logo" :src="casa.logo" class="w-14 h-14 object-contain" alt="">
                        <span x-show="!casa.logo" class="text-3xl">⚽</span>
                        <span class="font-bold text-sm truncate w-full" x-text="casa.nome"></span>
                    </template>
                    <template x-if="!casa">
                        <span class="text-brand-urban text-sm">Mandante</span>
                    </template>
                </div>
                <span class="text-xl font-black text-brand-orange-sand shrink-0">×</span>
                <div class="flex-1 flex flex-col items-center gap-2 text-center min-w-0">
                    <template x-if="fora">
                        <img x-show="fora.logo" :src="fora.logo" class="w-14 h-14 object-contain" alt="">
                        <span x-show="!fora.logo" class="text-3xl">⚽</span>
                        <span class="font-bold text-sm truncate w-full" x-text="fora.nome"></span>
                    </template>
                    <template x-if="!fora">
                        <span class="text-brand-urban text-sm">Visitante</span>
                    </template>
                </div>
            </div>
        </div>
    </template>

    {{ $slot }}
</div>
