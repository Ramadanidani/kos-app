<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah enum langsung lewat raw SQL — hindari Doctrine DBAL
        DB::statement("ALTER TABLE payment_reports MODIFY COLUMN status ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending'");

        // Tambah kolom rejection_reason secara normal (tanpa enum, jadi aman pakai Schema biasa)
        Schema::table('payment_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_reports', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE payment_reports MODIFY COLUMN status ENUM('pending', 'verified') NOT NULL DEFAULT 'pending'");

        Schema::table('payment_reports', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};