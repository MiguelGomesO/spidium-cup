<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participacoes_partida', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partida_id')->constrained('partidas')->cascadeOnDelete();
            $table->foreignId('jogador_id')->constrained('jogadores')->cascadeOnDelete();
            $table->decimal('nota', 4, 2)->nullable();
            $table->boolean('mvp')->default(false);
            $table->timestamps();

            $table->unique(['partida_id', 'jogador_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participacoes_partida');
    }
};
