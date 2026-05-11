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
        Schema::table('eventos_partida', function(Blueprint $table) {
            $table->string('tipo')->change();

            $table->foreignId('relacionado_id')->nullable()->after('minuto')->constrained('eventos_partida')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos_partida', function(Blueprint $table) {
            $table->dropForeign(['relacionado_id']);
            $table->dropColumn('relacionado_id');
        });
    }
};
