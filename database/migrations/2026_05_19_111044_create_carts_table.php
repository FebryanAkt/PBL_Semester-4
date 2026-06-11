<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            // Relasi ke user yang punya keranjang
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Relasi ke barang yang dimasukkan (asumsi nama tabel barangmu adalah 'items')
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();

            // Jumlah barang yang dibeli
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
