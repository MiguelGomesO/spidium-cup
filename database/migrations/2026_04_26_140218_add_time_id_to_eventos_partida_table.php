<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('eventos_partida', function (Blueprint $table) {
            $table->enum('time_id', ['casa', 'fora'])->after('partida_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos_partida', function (Blueprint $table) {
            $table->dropColumn('time_id');
        });
    }
};
