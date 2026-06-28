<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votos_partida', function (Blueprint $table) {
            $table->string('visitor_id')->nullable()->after('jogador_id');
            $table->string('ip_hash', 64)->nullable()->after('visitor_id');
            $table->text('user_agent')->nullable()->after('ip_hash');
        });

        $appKey = config('app.key');

        DB::table('votos_partida')
            ->whereNotNull('ip_address')
            ->orderBy('id')
            ->each(function (object $vote) use ($appKey): void {
                DB::table('votos_partida')
                    ->where('id', $vote->id)
                    ->update([
                        'ip_hash' => hash('sha256', $vote->ip_address . $appKey),
                    ]);
            });

        Schema::table('votos_partida', function (Blueprint $table) {
            $table->dropUnique(['partida_id', 'ip_address']);
            $table->dropColumn('ip_address');
        });

        Schema::table('votos_partida', function (Blueprint $table) {
            $table->unique(['partida_id', 'visitor_id']);
            $table->unique(['partida_id', 'ip_hash']);
        });
    }

    public function down(): void
    {
        Schema::table('votos_partida', function (Blueprint $table) {
            $table->dropUnique(['partida_id', 'visitor_id']);
            $table->dropUnique(['partida_id', 'ip_hash']);
        });

        Schema::table('votos_partida', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('jogador_id');
        });

        $appKey = config('app.key');

        DB::table('votos_partida')
            ->whereNotNull('ip_hash')
            ->orderBy('id')
            ->each(function (object $vote) use ($appKey): void {
                // Não é possível recuperar o IP original a partir do hash.
                DB::table('votos_partida')
                    ->where('id', $vote->id)
                    ->update([
                        'ip_address' => '0.0.0.0',
                    ]);
            });

        Schema::table('votos_partida', function (Blueprint $table) {
            $table->dropColumn(['visitor_id', 'ip_hash', 'user_agent']);
            $table->unique(['partida_id', 'ip_address']);
        });
    }
};
