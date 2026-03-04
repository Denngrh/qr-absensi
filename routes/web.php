<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ScanController;

// Public routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (admin only)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Mahasiswa routes
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::get('/mahasiswa/{mahasiswa}/qrcode', [MahasiswaController::class, 'showQrCode'])->name('mahasiswa.qrcode');

    // Panitia routes
    Route::resource('panitia', PanitiaController::class);
    Route::get('/panitia/{panitia}/qrcode', [PanitiaController::class, 'showQrCode'])->name('panitia.qrcode');

    // QR Code routes (untuk event lama - bisa dihapus jika tidak dipakai)
    Route::resource('qrcode', QrCodeController::class);

    // Scan QR routes (NEW)
    Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
    Route::post('/scan/process', [ScanController::class, 'process'])->name('scan.process');

    // Absensi routes
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
    Route::delete('/absensi/{absensi}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
});
