<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('times', function (Blueprint $table) {
            $table->foreignId('campeonato_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('times', function (Blueprint $table) {
            $table->dropForeign(['campeonato_id']);
            $table->dropColumn('campeonato_id');
        });
    }
};
