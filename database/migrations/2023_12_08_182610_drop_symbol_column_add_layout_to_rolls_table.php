<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rolls', function (Blueprint $table) {
            $table->dropColumn('symbol');
            $table->json('layout')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rolls', function (Blueprint $table) {
            $table->dropColumn('layout');
            $table->enum('symbol', ['cherry', 'lemon', 'orange', 'watermelon'])->after('user_id');
        });
    }
};
