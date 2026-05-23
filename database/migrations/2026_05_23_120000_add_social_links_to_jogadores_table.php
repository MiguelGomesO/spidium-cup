<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jogadores', function (Blueprint $table) {
            $table->string('instagram')->nullable()->after('numero');
            $table->string('twitter')->nullable()->after('instagram');
            $table->string('twitch')->nullable()->after('twitter');
        });
    }

    public function down(): void
    {
        Schema::table('jogadores', function (Blueprint $table) {
            $table->dropColumn(['instagram', 'twitter', 'twitch']);
        });
    }
};
