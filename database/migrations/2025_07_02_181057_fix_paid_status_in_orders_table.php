<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up()
    {
        DB::table('orders')->where('status', 'paid')->update(['status' => 'completed']);
    }

    public function down()
    {
        // Optional: rollback jika perlu
    }
};