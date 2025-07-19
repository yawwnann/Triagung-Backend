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
        Schema::table('order_items', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['product_id']);

            // Ubah kolom product_id menjadi unsigned bigint tanpa foreign key
            $table->unsignedBigInteger('product_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Kembalikan foreign key constraint
            $table->foreign('product_id')->references('id')->on('produks')->onDelete('restrict');
        });
    }
};