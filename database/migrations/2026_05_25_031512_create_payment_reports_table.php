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
        Schema::create('payment_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->string('period');              // Format: "2025-06" (tahun-bulan)
            $table->decimal('amount', 10, 2);      // Jumlah yang dibayar
            $table->string('method');              // Transfer/QRIS/Cash
            $table->string('proof_image');         // Foto bukti bayar
            $table->text('notes')->nullable();     // Catatan penghuni
            $table->enum('status', ['pending', 'verified'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_reports');
    }
};
