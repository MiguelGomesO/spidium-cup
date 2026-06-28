<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('melhores_momentos_partida', function (Blueprint $table) {
            $table->string('arquivo')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('melhores_momentos_partida', function (Blueprint $table) {
            $table->string('arquivo')->nullable(false)->change();
        });
    }
};
