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
        Schema::table('transactions', function (Blueprint $table) {
            // Menambahkan order_id dan snap_token setelah kolom status
            $table->string('order_id')->nullable()->after('status');
            $table->string('snap_token')->nullable()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn(['order_id', 'snap_token']);
        });
    }
};