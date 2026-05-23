<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');

            Schema::rename('partidas', 'partidas_old');

            Schema::create('partidas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('time_casa_id')->constrained('times')->cascadeOnDelete();
                $table->foreignId('time_fora_id')->constrained('times')->cascadeOnDelete();
                $table->integer('gols_casa')->default(0);
                $table->integer('gols_fora')->default(0);
                $table->string('status', 20)->default('em_andamento');
                $table->dateTime('data')->nullable();
                $table->foreignId('campeonato_id')->nullable()->constrained()->cascadeOnDelete();
                $table->string('fase')->nullable();
                $table->unsignedInteger('ordem')->nullable();
                $table->foreignId('proxima_partida_id')->nullable()->constrained('partidas')->nullOnDelete();
                $table->timestamps();
            });

            DB::statement('
                INSERT INTO partidas (
                    id, time_casa_id, time_fora_id, gols_casa, gols_fora, status, data,
                    campeonato_id, fase, ordem, proxima_partida_id, created_at, updated_at
                )
                SELECT
                    id, time_casa_id, time_fora_id, gols_casa, gols_fora, status, data,
                    campeonato_id, fase, ordem, proxima_partida_id, created_at, updated_at
                FROM partidas_old
            ');

            Schema::drop('partidas_old');
            DB::statement('PRAGMA foreign_keys = ON');

            return;
        }

        Schema::table('partidas', function (Blueprint $table) {
            $table->dateTime('data')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('partidas', function (Blueprint $table) {
            $table->dateTime('data')->nullable(false)->change();
        });
    }
};
