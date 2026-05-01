<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            //Relasi dengan users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('name');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->string('tags')->nullable(); // simpan comma
            $table->string('image')->nullable();
            $table->integer('price');
            $table->string('location');
            $table->string('condition')->default('Bekas');

            //STATUS: tersedia, terjual, atau disewa
            $table->enum('status', ['tersedia', 'booking', 'terjual'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};