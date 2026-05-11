<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampeonatoController;
use App\Http\Controllers\EventosPartidaController;
use App\Http\Controllers\JogadorController;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\TimeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('campeonatos', CampeonatoController::class)->middleware('auth');
    Route::resource('times', TimeController::class);
    Route::resource('partidas', PartidaController::class);
    Route::get('/campeonatos/{id}/classificacao', [CampeonatoController::class, 'classificacao'])->name('campeonatos.classificacao');
    Route::get('/campeonatos/{id}/chave', [CampeonatoController::class, 'chave'])->name('campeonatos.chave');
    Route::get('/times/{time}/escalacao', [TimeController::class, 'escalacao'])->name('times.escalacao');
    Route::get('/times/{time}/historico', [PartidaController::class, 'historico']);
    Route::get('/times/{time}/artilheiros', [PartidaController::class, 'artilheiros']);
    Route::post('/times/{time}/jogadores', [JogadorController::class, 'store'])->name('jogadores.store');
    Route::delete('jogadores/{jogador}', [JogadorController::class, 'destroy'])->name('jogadores.destroy');
    Route::post('/eventos', [EventosPartidaController::class, 'store'])->name('eventos.store');
    Route::delete('/eventos/{evento}', [EventosPartidaController::class, 'destroy'])->name('eventos.destroy');
    Route::post('/campeonatos/{campeonato}/times', [CampeonatoController::class, 'adicionarTime'])->name('campeonatos.times.store');
    Route::post('/campeonatos/{campeonato}/gerar-grupos', [CampeonatoController::class, 'gerarGrupos'])->name('campeonatos.gerar-grupos');
    Route::post('/campeonatos/{campeonato}/gerar-chaveamento', [CampeonatoController::class, 'gerarChaveamento'])->name('campeonatos.gerar-chaveamento');
});



require __DIR__ . '/auth.php';
