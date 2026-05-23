<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partidas', function (Blueprint $table) {
            $table->string('status', 20)->default('em_andamento')->after('gols_fora');
        });

        DB::table('partidas')->where('finalizada', true)->update(['status' => 'finalizada']);
        DB::table('partidas')->where('finalizada', false)->update(['status' => 'em_andamento']);

        Schema::table('partidas', function (Blueprint $table) {
            $table->dropColumn('finalizada');
        });
    }

    public function down(): void
    {
        Schema::table('partidas', function (Blueprint $table) {
            $table->boolean('finalizada')->default(false)->after('data');
        });

        DB::table('partidas')->where('status', 'finalizada')->update(['finalizada' => true]);

        Schema::table('partidas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
