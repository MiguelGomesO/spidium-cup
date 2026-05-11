<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos_partida', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partida_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jogador_id')->constrained('jogadores')->cascadeOnDelete();
            $table->foreignId('campeonato_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('tipo', [
                'gol',
                'assistencia',
                'cartao_amarelo',
                'cartao_vermelho'
            ]);
            $table->integer('minuto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_partida');
    }
};
