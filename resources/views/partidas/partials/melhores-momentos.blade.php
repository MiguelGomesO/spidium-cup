<div class="section-card">
    <div class="mb-6">
        <span class="inline-block px-3 py-1 rounded-full bg-brand-purple/15 border border-brand-purple/25 text-xs text-brand-lilac mb-3">
            Destaques
        </span>
        <h2 class="text-lg sm:text-xl font-bold">Melhores momentos</h2>
        <p class="text-sm text-brand-ice/50 mt-1">
            Clips da Twitch, fotos ou vídeos marcantes desta partida.
        </p>
    </div>

    @auth
        <div
            class="mb-8 p-4 sm:p-5 rounded-2xl border border-brand-ice/10 bg-brand-black/30"
            x-data="{ fonte: @js(old('fonte', 'twitch')) }"
        >
            <h3 class="font-semibold text-sm mb-1">Adicionar momento</h3>
            <p class="text-xs text-brand-ice/45 mb-4">Cole o link de um clip da Twitch ou envie um arquivo (até 50 MB).</p>

            <form
                method="POST"
                action="{{ route('partidas.momentos.store', $partida) }}"
                enctype="multipart/form-data"
                class="space-y-4"
            >
                @csrf

                <input type="hidden" name="fonte" :value="fonte">

                <div class="tabs-scroll" role="tablist">
                    <button
                        type="button"
                        @click="fonte = 'twitch'"
                        :class="fonte === 'twitch' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'"
                    >
                        Clip Twitch
                    </button>
                    <button
                        type="button"
                        @click="fonte = 'upload'"
                        :class="fonte === 'upload' ? 'tab-pill tab-pill--active' : 'tab-pill tab-pill--idle'"
                    >
                        Arquivo
                    </button>
                </div>

                <div>
                    <label for="momento_titulo" class="text-xs text-brand-ice/70 mb-1 block">Título (opcional)</label>
                    <input
                        id="momento_titulo"
                        type="text"
                        name="titulo"
                        value="{{ old('titulo') }}"
                        maxlength="120"
                        placeholder="Ex: Gol de falta no finzinho"
                        class="input-touch text-sm"
                    >
                </div>

                <div x-show="fonte === 'twitch'" x-transition>
                    <label for="momento_twitch_url" class="text-xs text-brand-ice/70 mb-1 block">Link do clip</label>
                    <input
                        id="momento_twitch_url"
                        type="url"
                        name="twitch_url"
                        value="{{ old('twitch_url') }}"
                        placeholder="https://clips.twitch.tv/NomeDoClip"
                        class="input-touch text-sm"
                        :required="fonte === 'twitch'"
                    >
                    <p class="text-xs text-brand-ice/40 mt-1.5">
                        Aceita links como <span class="text-brand-ice/55">clips.twitch.tv/...</span> ou <span class="text-brand-ice/55">twitch.tv/canal/clip/...</span>
                    </p>
                    @error('twitch_url')
                        <p class="text-sm text-brand-orange mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="fonte === 'upload'" x-transition x-cloak>
                    <label for="momento_arquivo" class="text-xs text-brand-ice/70 mb-1 block">Arquivo</label>
                    <input
                        id="momento_arquivo"
                        type="file"
                        name="arquivo"
                        accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm"
                        class="input-touch text-sm file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-brand-purple/30 file:text-brand-ice file:text-xs"
                        :required="fonte === 'upload'"
                    >
                    @error('arquivo')
                        <p class="text-sm text-brand-orange mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="momento_descricao" class="text-xs text-brand-ice/70 mb-1 block">Descrição (opcional)</label>
                    <textarea
                        id="momento_descricao"
                        name="descricao"
                        rows="2"
                        maxlength="500"
                        placeholder="Conte o que aconteceu neste lance..."
                        class="input-touch text-sm resize-none"
                    >{{ old('descricao') }}</textarea>
                </div>

                @error('titulo')
                    <p class="text-sm text-brand-orange">{{ $message }}</p>
                @enderror
                @error('descricao')
                    <p class="text-sm text-brand-orange">{{ $message }}</p>
                @enderror
                @error('fonte')
                    <p class="text-sm text-brand-orange">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn-brand text-sm">
                    Publicar momento
                </button>
            </form>
        </div>
    @endauth

    @if ($partida->momentos->isEmpty())
        <div class="text-center py-12 rounded-2xl border border-dashed border-brand-ice/10 bg-brand-black/20">
            <div class="text-4xl mb-3">🎬</div>
            <p class="text-brand-ice/50 text-sm">
                @auth
                    Nenhum momento publicado ainda. Use o formulário acima para enviar o primeiro.
                @else
                    Nenhum momento publicado para esta partida ainda.
                @endauth
            </p>
        </div>
    @else
        <div class="momentos-grid">
            @foreach ($partida->momentos as $momento)
                <article @class([
                    'momento-card group',
                    'momento-card--wide' => $momento->isTwitchClip() || $momento->isVideo(),
                ])>
                    <div class="momento-card__media">
                        @if ($momento->isTwitchClip())
                            <iframe
                                src="{{ $momento->twitchEmbedUrl() }}"
                                title="{{ $momento->titulo ?? 'Clip da Twitch' }}"
                                allowfullscreen
                                loading="lazy"
                                class="momento-card__twitch"
                            ></iframe>
                        @elseif ($momento->isVideo())
                            <video
                                src="{{ $momento->url() }}"
                                controls
                                playsinline
                                preload="metadata"
                                class="momento-card__video"
                            ></video>
                        @else
                            <img
                                src="{{ $momento->url() }}"
                                alt="{{ $momento->titulo ?? 'Momento da partida' }}"
                                class="momento-card__image"
                                loading="lazy"
                            >
                        @endif
                    </div>

                    @if ($momento->titulo || $momento->descricao || $momento->isTwitchClip())
                        <div class="momento-card__body">
                            @if ($momento->titulo)
                                <h3 class="font-semibold text-sm">{{ $momento->titulo }}</h3>
                            @endif
                            @if ($momento->descricao)
                                <p class="text-xs text-brand-ice/55 mt-1 leading-relaxed">{{ $momento->descricao }}</p>
                            @endif
                            @if ($momento->isTwitchClip())
                                <a
                                    href="{{ $momento->url() }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-block text-xs text-brand-lilac hover:text-brand-orange-sand mt-2 transition"
                                >
                                    Abrir na Twitch ↗
                                </a>
                            @endif
                        </div>
                    @endif

                    @auth
                        <form
                            method="POST"
                            action="{{ route('partidas.momentos.destroy', [$partida, $momento]) }}"
                            class="momento-card__actions"
                            onsubmit="return confirm('Remover este momento?')"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="momento-card__delete" title="Remover">
                                ✕
                            </button>
                        </form>
                    @endauth
                </article>
            @endforeach
        </div>
    @endif
</div>
