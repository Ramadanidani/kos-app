<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\RoomController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\TransferRequestController;
use App\Http\Controllers\Tenant\AuthController as TenantAuthController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
use App\Http\Controllers\Tenant\PaymentController as TenantPaymentController;
use App\Http\Controllers\Tenant\ComplaintController as TenantComplaintController;
use App\Http\Controllers\Tenant\TransferController as TenantTransferController;
use App\Http\Controllers\Admin\PaymentInfoController;
use App\Http\Controllers\Admin\PaymentReportController as AdminPaymentReportController;
use App\Http\Controllers\Tenant\PaymentReportController as TenantPaymentReportController;
use App\Http\Controllers\Admin\WhatsAppController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Tenant\AnnouncementController as TenantAnnouncementController;


// ── PUBLIC ROUTES ──
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/masuk', fn() => view('auth.select-role'))->name('select-role');

Route::prefix('kamar')->name('rooms.')->group(function () {
    Route::get('/',       [RoomController::class, 'index'])->name('index');
    Route::get('/{room}', [RoomController::class, 'show'])->name('show');
});

// ── AUTH PROFILE ──
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── REDIRECT SETELAH LOGIN ──
Route::get('/dashboard', fn() => redirect()->route('admin.dashboard'))
    ->middleware(['auth'])->name('dashboard');

// ── ADMIN ROUTES ──
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('rooms',     AdminRoomController::class);
    Route::resource('tenants',   TenantController::class);
    Route::resource('payments',  PaymentController::class);
    Route::resource('complaints', ComplaintController::class);
    Route::resource('transfers', TransferRequestController::class);

    Route::patch('transfers/{transfer}/approve', [TransferRequestController::class, 'approve'])->name('transfers.approve');
    Route::patch('transfers/{transfer}/reject',  [TransferRequestController::class, 'reject'])->name('transfers.reject');

    Route::post('rooms/{room}/primary-photo', [AdminRoomController::class, 'setPrimaryPhoto'])->name('rooms.primary-photo');

    Route::post('payments/{payment}/confirm',   [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::post('payments/generate-monthly',    [PaymentController::class, 'generateMonthly'])->name('payments.generate-monthly');

    Route::get('payment-info',  [PaymentInfoController::class, 'edit'])->name('payment-info.edit');
    Route::put('payment-info',  [PaymentInfoController::class, 'update'])->name('payment-info.update');

    Route::get('payment-reports',                    [AdminPaymentReportController::class, 'index'])->name('payment-reports.index');
    Route::get('payment-reports/{paymentReport}',    [AdminPaymentReportController::class, 'show'])->name('payment-reports.show');
    Route::post('payment-reports/{paymentReport}/verify', [AdminPaymentReportController::class, 'verify'])->name('payment-reports.verify');

    // WhatsApp Reminder
    Route::post('whatsapp/reminder/{payment}',        [WhatsAppController::class, 'sendReminder'])->name('whatsapp.reminder');
    Route::post('whatsapp/reminder-bulk',             [WhatsAppController::class, 'sendBulkReminder'])->name('whatsapp.bulk-reminder');
    Route::post('whatsapp/reminder-tenant/{tenant}',  [WhatsAppController::class, 'sendTenantReminder'])->name('whatsapp.tenant-reminder');
    Route::get('whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp.index');

    // Pengumuman
    Route::resource('announcements', AdminAnnouncementController::class);
});

// ── TENANT AUTH ──
Route::prefix('penghuni')->name('tenant.')->group(function () {

    Route::middleware('guest:tenant')->group(function () {
        Route::get('/login',  [TenantAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [TenantAuthController::class, 'login'])->name('login.post');
    });

    Route::middleware('tenant')->group(function () {

        // Dashboard
        Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout',   [TenantAuthController::class, 'logout'])->name('logout');

        // Password
        Route::get('/ganti-password',  [TenantAuthController::class, 'showChangePassword'])->name('password.change');
        Route::post('/ganti-password', [TenantAuthController::class, 'updatePassword'])->name('password.update');

        // Tagihan
        Route::get('/tagihan', [TenantPaymentController::class, 'index'])->name('payments.index');

        // Keluhan
        Route::get('/keluhan',          [TenantComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/keluhan/buat',     [TenantComplaintController::class, 'create'])->name('complaints.create');
        Route::post('/keluhan',         [TenantComplaintController::class, 'store'])->name('complaints.store');
        Route::get('/keluhan/{complaint}', [TenantComplaintController::class, 'show'])->name('complaints.show');

        // Pindah Kamar
        Route::get('/pindah-kamar',      [TenantTransferController::class, 'index'])->name('transfers.index');
        Route::get('/pindah-kamar/buat', [TenantTransferController::class, 'create'])->name('transfers.create');
        Route::post('/pindah-kamar',     [TenantTransferController::class, 'store'])->name('transfers.store');

        // Laporan Pembayaran
        Route::get('/laporan-bayar',        [TenantPaymentReportController::class, 'index'])->name('payment-reports.index');
        Route::get('/laporan-bayar/kirim',  [TenantPaymentReportController::class, 'create'])->name('payment-reports.create');
        Route::post('/laporan-bayar',       [TenantPaymentReportController::class, 'store'])->name('payment-reports.store');
        Route::get('/laporan-bayar/{paymentReport}', [TenantPaymentReportController::class, 'show'])->name('payment-reports.show');

        // Pengumuman
        Route::get('/pengumuman',                        [TenantAnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('/pengumuman/{announcement}',         [TenantAnnouncementController::class, 'show'])->name('announcements.show');
        Route::post('/pengumuman/{announcement}/reaksi', [TenantAnnouncementController::class, 'react'])->name('announcements.react');


    });
});




require __DIR__.'/auth.php';