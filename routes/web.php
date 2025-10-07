<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PengajuanController;

// Root -> redirect sesuai login
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('karyawan.index');
    }
    return redirect()->route('login');
});

// Login hanya untuk guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================== ADMIN ==================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/pengajuan', [PengajuanController::class, 'index'])->name('admin.pengajuan');
    Route::post('/admin/pengajuan/{id}/status', [PengajuanController::class, 'updateStatus'])->name('pengajuan.updateStatus');

    // CRUD Karyawan
    Route::get('/admin/karyawan', [AdminController::class, 'karyawan'])->name('admin.karyawan');
    Route::get('/admin/karyawan/create', [AdminController::class, 'createKaryawan'])->name('admin.karyawan.create');
    Route::post('/admin/karyawan', [AdminController::class, 'storeKaryawan'])->name('admin.karyawan.store');
    Route::get('/admin/karyawan/{id}/edit', [AdminController::class, 'editKaryawan'])->name('admin.karyawan.edit');
    Route::put('/admin/karyawan/{id}', [AdminController::class, 'updateKaryawan'])->name('admin.karyawan.update');
    Route::delete('/admin/karyawan/{id}', [AdminController::class, 'destroyKaryawan'])->name('admin.karyawan.destroy');

    // Fitur admin lainnya
    Route::get('/admin/approval-workflow', [AdminController::class, 'approvalWorkflow'])->name('admin.approval-workflow');
    Route::get('/admin/payroll', [App\Http\Controllers\AdminController::class, 'payroll'])->name('admin.payroll');
    Route::get('/admin/bonus', [App\Http\Controllers\AdminController::class, 'bonus'])->name('admin.bonus');


    // Generate Absen
    Route::get('/admin/absen', [AdminController::class, 'absensi'])->name('admin.absen');
});


// ================== KARYAWAN ==================
Route::middleware(['auth', 'role:karyawan'])->group(function () {
    Route::get('/karyawan', [KaryawanController::class, 'showDashboard'])->name('karyawan.index');
    Route::get('/karyawan/absen', [KaryawanController::class, 'showAbsen'])->name('karyawan.absen');
    Route::post('/karyawan/absen', [KaryawanController::class, 'storeAbsen'])->name('karyawan.absen.store');

    // Pengajuan
Route::get('/karyawan/pengajuan', [PengajuanController::class, 'indexKaryawan'])->name('karyawan.pengajuan');
Route::post('/karyawan/pengajuan', [PengajuanController::class, 'store'])->name('karyawan.pengajuan.store');
});

