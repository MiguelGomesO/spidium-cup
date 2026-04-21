<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampeonatoController;
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
});

Route::resource('campeonatos', CampeonatoController::class)->middleware('auth');

Route::resource('times', TimeController::class)->middleware('auth');

Route::resource('partidas', PartidaController::class);

Route::get('/campeonatos/{id}/classificacao', [CampeonatoController::class, 'classificacao'])->name('campeonatos.classificacao');

Route::get('/campeonato/{id}/chave', [CampeonatoController::class, 'chave'])->name('campeonatos.chave');

Route::get('/times/{time}/escalacao', [TimeController::class, 'escalacao'])->name('times.escalacao');

Route::get('/times/{time}/historico', [PartidaController::class, 'historico']);

Route::get('/times/{time}/artilheiros', [PartidaController::class, 'artilheiros']);

Route::post('/times/{time}/jogadores', [JogadorController::class, 'store'])->name('jogadores.store');

Route::delete('jogadores/{jogador}', [JogadorController::class, 'destroy'])->name('jogadores.destroy');

require __DIR__.'/auth.php';
