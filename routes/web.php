<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
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

    // ===== KARYAWAN (DUMMY TANPA DATABASE) =====
    Route::get('/karyawan', function (Request $request) {
        $all = collect([
            (object)['id' => 1, 'nama' => 'Budi Santoso', 'email' => 'budi@example.com'],
            (object)['id' => 2, 'nama' => 'Siti Aminah', 'email' => 'siti@example.com'],
            (object)['id' => 3, 'nama' => 'Agus Prasetyo', 'email' => 'agus@example.com'],
            (object)['id' => 4, 'nama' => 'Rina Putri', 'email' => 'rina@example.com'],
            (object)['id' => 5, 'nama' => 'Dedi Wijaya', 'email' => 'dedi@example.com'],
        ]);

        $perPage = 10;
        $page = max(1, (int) $request->query('page', 1));
        $slice = $all->forPage($page, $perPage);
        $employees = new LengthAwarePaginator(
            $slice->values(),
            $all->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.karyawan.karyawan', compact('employees'));
    })->name('karyawan');

    // ===== FORM TAMBAH KARYAWAN (DUMMY) =====
    Route::get('/karyawan/create', function () {
        return view('admin.karyawan.karyawan-create');
    })->name('karyawan.create');

    // ===== SIMPAN DATA BARU (DUMMY) =====
    Route::post('/karyawan', function (Request $request) {
        return redirect()->route('admin.karyawan')->with('success', 'Data karyawan berhasil ditambahkan (dummy).');
    })->name('karyawan.store');

    // ===== FORM EDIT KARYAWAN (DUMMY) =====
    Route::get('/karyawan/{id}/edit', function ($id) {
        $karyawan = (object)[
            'id' => $id,
            'name' => 'Karyawan ' . $id,
            'email' => 'karyawan' . $id . '@example.com',
        ];
        return view('admin.karyawan.karyawan-edit', compact('karyawan'));
    })->name('karyawan.edit');

    // ===== UPDATE KARYAWAN (DUMMY) =====
    Route::put('/karyawan/{id}', function (Request $request, $id) {
        return redirect()->route('admin.karyawan')->with('success', 'Data karyawan berhasil diperbarui (dummy).');
    })->name('karyawan.update');

    // ===== HAPUS KARYAWAN (DUMMY) =====
    Route::delete('/karyawan/{id}', function ($id) {
        return redirect()->route('admin.karyawan')->with('success', 'Data karyawan berhasil dihapus (dummy).');
    })->name('karyawan.destroy');

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
