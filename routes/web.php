<?php
// Route download lampiran pengajuan (umum, bisa untuk admin/karyawan)
Route::get('/download/lampiran/{filename}', [App\Http\Controllers\PengajuanController::class, 'downloadLampiran'])->name('lampiran.download');

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KaryawanController;
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
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
                    // Slip Gaji Admin
                    Route::get('/slip-gaji', [App\Http\Controllers\Admin\SlipGajiController::class, 'index'])->name('slipgaji.index');
                    Route::post('/slip-gaji/proses/{id}', [App\Http\Controllers\Admin\SlipGajiController::class, 'proses'])->name('slipgaji.proses');
                    Route::post('/slip-gaji/proses-semua', [App\Http\Controllers\Admin\SlipGajiController::class, 'prosesSemua'])->name('slipgaji.prosesSemua');
                Route::post('/bon/batal/{id}', [App\Http\Controllers\AdminBonGajiController::class, 'batal'])->name('bon.batal');
            // Bon Gaji - Admin
            Route::get('/bon', [App\Http\Controllers\AdminBonGajiController::class, 'index'])->name('bon.index');
            Route::post('/bon/approve/{id}', [App\Http\Controllers\AdminBonGajiController::class, 'approve'])->name('bon.approve');
            Route::post('/bon/reject/{id}', [App\Http\Controllers\AdminBonGajiController::class, 'reject'])->name('bon.reject');
        // Route lembur admin hanya ke controller yang benar
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // CRUD Karyawan
    Route::get('/karyawan', [AdminController::class, 'karyawan'])->name('karyawan.index');
    Route::get('/karyawan/create', [AdminController::class, 'createKaryawan'])->name('karyawan.create');
    Route::post('/karyawan', [AdminController::class, 'storeKaryawan'])->name('karyawan.store');
    Route::get('/karyawan/{id}', [AdminController::class, 'getKaryawan'])->name('karyawan.detail');
    Route::put('/karyawan/{id}', [AdminController::class, 'updateKaryawan'])->name('karyawan.update');

    // Absensi
    Route::get('/absen', [AdminController::class, 'absensi'])->name('absen');

 // List semua karyawan
    Route::get('/pengajuan', [PengajuanController::class, 'listKaryawan'])
        ->name('pengajuan.index');

    // List pengajuan berdasarkan karyawan yg diklik
    Route::get('/pengajuan/{id}', [PengajuanController::class, 'pengajuanByKaryawan'])
        ->name('pengajuan.detail');

    // Detail cuti approval
    Route::get('/approval/detailcuti', [AdminController::class, 'detailCuti'])->name('approval.detailcuti');

    // ACC
    Route::post('/pengajuan/acc/{id}', [PengajuanController::class, 'acc'])
        ->name('pengajuan.acc');

    // Tolak
    Route::post('/pengajuan/tolak/{id}', [PengajuanController::class, 'tolak'])
        ->name('pengajuan.tolak');

    // Batalkan
    Route::post('/pengajuan/batal/{id}', [PengajuanController::class, 'batal'])
        ->name('pengajuan.batal');

    // Lembur - Admin
    Route::get('/lembur', [App\Http\Controllers\AdminLemburController::class, 'index'])->name('lembur.index');
    Route::post('/lembur/setujui/{id}', [App\Http\Controllers\AdminLemburController::class, 'approve'])->name('lembur.setujui');
    Route::post('/lembur/tolak/{id}', [App\Http\Controllers\AdminLemburController::class, 'reject'])->name('lembur.tolak');
    Route::post('/lembur/batal/{id}', [App\Http\Controllers\AdminLemburController::class, 'batal'])->name('lembur.batal');
});
// ================== KARYAWAN ==================
Route::middleware(['auth', 'role:karyawan'])->group(function () {
    Route::get('/karyawan', [KaryawanController::class, 'showDashboard'])->name('karyawan.index');
    Route::get('/karyawan/absen', [KaryawanController::class, 'showAbsen'])->name('karyawan.absen');
    Route::post('/karyawan/absen', [KaryawanController::class, 'storeAbsen'])->name('karyawan.absen.store');
    Route::get('/karyawan/dashboard', [KaryawanController::class, 'showDashboard'])
    ->name('karyawan.dashboard');


    // Absen
    Route::post('/absen/clockin', [KaryawanController::class, 'clockIn'])->name('absen.clockin');
    Route::post('/absen/clockout', [KaryawanController::class, 'clockOut'])->name('absen.clockout');

    // ✅ Tambahan baru — Cek status Clock In (JSON)
    Route::get('/absen/status', [KaryawanController::class, 'checkClockInStatus'])->name('absen.status');

    // Pengajuan
    Route::get('/pengajuan', [PengajuanController::class, 'indexKaryawan'])->name('pengajuan.karyawan');
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan/store', [PengajuanController::class, 'store'])->name('pengajuan.store');

    // ================== DATA KARYAWAN & JADWAL ==================
    Route::get('/karyawan/data', [KaryawanController::class, 'dataKaryawan'])->name('karyawan.data');
    Route::get('/karyawan/jadwal', [KaryawanController::class, 'dataKaryawan'])->name('karyawan.jadwal');

    // ================== DATA KARYAWAN & JADWAL ==================
    Route::get('/karyawan/data', [KaryawanController::class, 'dataKaryawan'])->name('karyawan.data');
    Route::get('/karyawan/jadwal', [KaryawanController::class, 'dataKaryawan'])->name('karyawan.jadwal');

    // ================== BON GAJI ==================
    Route::get('/karyawan/bon', [App\Http\Controllers\BonGajiController::class, 'index'])->name('karyawan.bon');
    Route::post('/karyawan/bon', [App\Http\Controllers\BonGajiController::class, 'store'])->name('karyawan.bon.store');

    // ================== LEMBUR (DUMMY) ==================
    Route::get('/karyawan/lembur', [App\Http\Controllers\LemburController::class, 'indexKaryawan'])->name('karyawan.lembur');
    Route::post('/karyawan/lembur', [App\Http\Controllers\LemburController::class, 'store'])->name('lembur.store');
});
