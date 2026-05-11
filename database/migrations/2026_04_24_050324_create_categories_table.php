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
        // 1. Buat tabel categories terlebih dahulu
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama kategori untuk barang bekas
            $table->string('slug')->unique()->nullable(); // Opsional: berguna jika butuh URL ramah SEO
            $table->timestamps();
        });

        // 2. Setelah tabel categories ada, baru tambahkan foreign key di tabel items
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Saat rollback, hapus relasi di items dulu sebelum menghapus tabel categories
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('categories');
    }
};