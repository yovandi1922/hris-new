<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminKaryawanController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\KaryawanController;

// ================== REDIRECT UTAMA ==================
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

// ================== ADMIN AREA ==================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // ===== DASHBOARD =====
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // ===== CRUD KARYAWAN =====
    Route::get('/karyawan', [AdminKaryawanController::class, 'index'])->name('karyawan');
    Route::get('/karyawan/create', [AdminKaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('/karyawan/store', [AdminKaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('/karyawan/{id}/edit', [AdminKaryawanController::class, 'edit'])->name('karyawan.edit');
    Route::put('/karyawan/{id}', [AdminKaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{id}', [AdminKaryawanController::class, 'destroy'])->name('karyawan.destroy');

    // ===== ABSENSI =====
    Route::get('/absen', [AdminController::class, 'absensi'])->name('absen');

    // ===== APPROVAL =====
    Route::prefix('approval')->name('approval.')->group(function () {
        Route::view('/cutiizin', 'admin.approval.cutiizin')->name('cutiizin');
        Route::view('/lembur', 'admin.approval.lembur')->name('lembur');
        Route::view('/bon', 'admin.approval.bon')->name('bon');
    });

    // ===== PAYROLL =====
    Route::prefix('payroll')->name('payroll.')->group(function () {
        Route::view('/gaji', 'admin.payroll.gaji')->name('gaji');
        Route::view('/bon', 'admin.payroll.bon')->name('bon');
        Route::view('/bonus', 'admin.payroll.bonus')->name('bonus');
    });

    // ===== JADWAL, REKAP, PENGATURAN =====
    Route::view('/jadwal', 'admin.jadwal')->name('jadwal');
    Route::view('/rekap', 'admin.rekap')->name('rekap');
    Route::view('/pengaturan', 'admin.pengaturan')->name('pengaturan');
});

// ================== KARYAWAN AREA ==================
Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        // DASHBOARD
        Route::get('/', [KaryawanController::class, 'showDashboard'])->name('index');

        // ABSENSI
        Route::get('/absen', [KaryawanController::class, 'showAbsen'])->name('absen');
        Route::post('/absen', [KaryawanController::class, 'storeAbsen'])->name('absen.store');

        // PENGAJUAN (cuti, lembur, bon, dll)
        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan');
        Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan/store', [PengajuanController::class, 'store'])->name('pengajuan.store');
    });
