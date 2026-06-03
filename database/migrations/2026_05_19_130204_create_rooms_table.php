<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_rooms_table.php
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // Nama kamar, misal "Kamar A1"
            $table->string('type');               // Standard, Deluxe, VIP
            $table->decimal('price', 10, 2);      // Harga per bulan
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->integer('size');              // Ukuran dalam m²
            $table->integer('floor')->default(1);
            $table->text('description')->nullable();
            $table->json('facilities')->nullable(); // ["WiFi", "AC", "Kamar Mandi Dalam"]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
