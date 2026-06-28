<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votos_partida', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partida_id')->constrained('partidas')->cascadeOnDelete();
            $table->foreignId('jogador_id')->constrained('jogadores')->cascadeOnDelete();
            $table->string('ip_address', 45);
            $table->timestamps();

            $table->unique(['partida_id', 'ip_address']);
            $table->index(['partida_id', 'jogador_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votos_partida');
    }
};
