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
        if (!Schema::hasColumn('produks', 'berat')) {
            Schema::table('produks', function (Blueprint $table) {
                $table->integer('berat')->default(1000)->after('harga');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('produks', 'berat')) {
            Schema::table('produks', function (Blueprint $table) {
                $table->dropColumn('berat');
            });
        }
    }
};
