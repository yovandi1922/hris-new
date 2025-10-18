<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PengajuanController;

// Redirect utama
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('karyawan.index');
    }
    return redirect()->route('login');
});

// ================== LOGIN / LOGOUT ==================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================== ADMIN ==================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // Karyawan
    Route::view('/karyawan', 'admin.karyawan.karyawan')->name('karyawan');
    Route::view('/karyawan/create', 'admin.karyawan.karyawan-create')->name('karyawan.create');
    Route::view('/karyawan/edit', 'admin.karyawan.karyawan-edit')->name('karyawan.edit');

    // Absensi
    Route::view('/absen', 'admin.absen')->name('absen');

    // Approval Dropdown
    Route::prefix('approval')->name('approval.')->group(function () {
        Route::view('/cutiizin', 'admin.approval.cutiizin')->name('cutiizin');
        Route::view('/lembur', 'admin.approval.lembur')->name('lembur');
        Route::view('/bon', 'admin.approval.bon')->name('bon');
    });

    // Payroll Dropdown
    Route::prefix('payroll')->name('payroll.')->group(function () {
        Route::view('/gaji', 'admin.payroll.gaji')->name('gaji');
        Route::view('/bon', 'admin.payroll.bon')->name('bon');
        Route::view('/bonus', 'admin.payroll.bonus')->name('bonus');
    });

    // Jadwal, Rekap, dan Pengaturan
    Route::view('/jadwal', 'admin.jadwal')->name('jadwal');
    Route::view('/rekap', 'admin.rekap')->name('rekap');
    Route::view('/pengaturan', 'admin.pengaturan')->name('pengaturan');
});