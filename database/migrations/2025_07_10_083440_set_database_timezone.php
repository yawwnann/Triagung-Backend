<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set timezone untuk database
        DB::statement("SET time_zone = '+07:00'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset timezone ke UTC
        DB::statement("SET time_zone = '+00:00'");
    }
};
