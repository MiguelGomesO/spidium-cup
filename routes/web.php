<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampeonatoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventosPartidaController;
use App\Http\Controllers\JogadorController;
use App\Http\Controllers\ParticipacaoPartidaController;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\TimeController;
use App\Http\Controllers\NotaPublicaPartidaController;
use App\Http\Controllers\MelhorMomentoPartidaController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/resultados');

Route::get('/resultados', [ResultadosController::class, 'index'])->name('resultados.index');
Route::get('/resultados/{campeonato}', [ResultadosController::class, 'show'])->name('resultados.show');

Route::get('/partidas/{partida}', [PartidaController::class, 'show'])
    ->whereNumber('partida')
    ->name('partidas.show');
Route::post('/partidas/{partida}/votos', [VotoPartidaController::class, 'store'])
    ->whereNumber('partida')
    ->middleware('throttle:voting')
    ->name('partidas.votos.store');
Route::post('/partidas/{partida}/notas', [NotaPublicaPartidaController::class, 'store'])
    ->whereNumber('partida')
    ->name('partidas.notas.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('campeonatos', CampeonatoController::class)->middleware('auth');
    Route::resource('times', TimeController::class);
    Route::resource('partidas', PartidaController::class)->except(['show']);
    Route::get('/campeonatos/{id}/classificacao', [CampeonatoController::class, 'classificacao'])->name('campeonatos.classificacao');
    Route::get('/campeonatos/{id}/chave', [CampeonatoController::class, 'chave'])->name('campeonatos.chave');
    Route::get('/times/{time}/escalacao', [TimeController::class, 'escalacao'])->name('times.escalacao');
    Route::get('/times/{time}/historico', [PartidaController::class, 'historico']);
    Route::get('/times/{time}/artilheiros', [PartidaController::class, 'artilheiros']);
    Route::post('/times/{time}/jogadores', [JogadorController::class, 'store'])->name('jogadores.store');
    Route::put('/jogadores/{jogador}', [JogadorController::class, 'update'])->name('jogadores.update');
    Route::delete('jogadores/{jogador}', [JogadorController::class, 'destroy'])->name('jogadores.destroy');
    Route::post('/eventos', [EventosPartidaController::class, 'store'])->name('eventos.store');
    Route::delete('/eventos/{evento}', [EventosPartidaController::class, 'destroy'])->name('eventos.destroy');
    Route::post('/campeonatos/{campeonato}/times', [CampeonatoController::class, 'adicionarTime'])->name('campeonatos.times.store');
    Route::post('/campeonatos/{campeonato}/gerar-grupos', [CampeonatoController::class, 'gerarGrupos'])->name('campeonatos.gerar-grupos');
    Route::post('/campeonatos/{campeonato}/gerar-chaveamento', [CampeonatoController::class, 'gerarChaveamento'])->name('campeonatos.gerar-chaveamento');
    Route::post('/campeonatos/{campeonato}/grupos', [CampeonatoController::class, 'storeGrupo'])->name('campeonatos.grupos.store');
    Route::post('/grupos/{grupo}/times', [CampeonatoController::class, 'adicionarTimeGrupo'])->name('grupos.times.store');
    Route::delete('/grupos/{grupo}/times/{time}', [CampeonatoController::class, 'removerTimeGrupo'])->name('grupos.times.destroy');
    Route::delete('/grupos/{grupo}', [CampeonatoController::class, 'destroyGrupo'])->name('grupos.destroy');
    Route::post('/campeonatos/{campeonato}/gerar-partidas-grupos', [CampeonatoController::class, 'gerarPartidasGrupos'])->name('campeonatos.gerar-partidas-grupos');
    Route::post('/campeonatos/{campeonato}/partidas', [CampeonatoController::class, 'storePartida'])->name('campeonatos.partidas.store');
    Route::patch('/partidas/{partida}/finalizar', [PartidaController::class, 'finalizar'])->name('partidas.finalizar');
    Route::patch('/partidas/{partida}/status', [PartidaController::class, 'atualizarStatus'])->name('partidas.status');
    Route::post('/partidas/{partida}/participacoes', [ParticipacaoPartidaController::class, 'sync'])->name('partidas.participacoes.sync');
    Route::post('/partidas/{partida}/momentos', [MelhorMomentoPartidaController::class, 'store'])->name('partidas.momentos.store');
    Route::delete('/partidas/{partida}/momentos/{momento}', [MelhorMomentoPartidaController::class, 'destroy'])->name('partidas.momentos.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});



require __DIR__ . '/auth.php';
