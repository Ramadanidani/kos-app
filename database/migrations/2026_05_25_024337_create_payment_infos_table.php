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
        Schema::create('payment_infos', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name')->nullable();      // BCA, Mandiri, dll
            $table->string('account_number')->nullable(); // No rekening
            $table->string('account_name')->nullable();   // Nama pemilik rekening
            $table->string('qris_image')->nullable();     // Path foto QRIS
            $table->string('whatsapp')->nullable();       // No WA admin
            $table->text('notes')->nullable();            // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_infos');
    }
};
