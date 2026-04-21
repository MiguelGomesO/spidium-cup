<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('time_casa_id')->constrained('times')->cascadeOnDelete();
            $table->foreignId('time_fora_id')->constrained('times')->cascadeOnDelete();
            $table->integer('gols_casa')->default(0);
            $table->integer('gols_fora')->default(0);
            $table->date('data');
            $table->foreignId('campeonato_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partidas');
    }
};
