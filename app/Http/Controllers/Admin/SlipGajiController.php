<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\SlipGaji;
use Illuminate\Support\Facades\Schema;

class SlipGajiController extends Controller
{
    // 1. Tampilkan halaman slip gaji admin
    public function index(Request $request)
    {
        $bulan = (int) $request->input('bulan', date('n'));
        $tahun = (int) $request->input('tahun', date('Y'));

        // Ambil semua karyawan (bisa filter aktif jika ada kolom work_status)
        $karyawanList = \App\Models\User::query()
            ->when(function ($q) {
                return Schema::hasColumn('users', 'role');
            }, function ($q) {
                $q->where('role', 'karyawan');
            })
            ->get();

        // Ambil slip gaji periode (jika ada)
        $slipGajiPeriode = \App\Models\SlipGaji::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        return view('admin.slip-gaji.index', [
            'karyawanList' => $karyawanList,
            'slipGajiPeriode' => $slipGajiPeriode,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);
    }

    // 2. Proses gaji satu karyawan
    public function proses($karyawan_id, Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($karyawan_id);
            // Ambil data absensi, lembur, telat, izin, cuti dari relasi/model sesuai kebutuhan
            $lembur_jam = $user->lembur_jam_periode($request->bulan, $request->tahun); // implementasikan di model User
            $telat_jam = $user->telat_jam_periode($request->bulan, $request->tahun);
            $izin = $user->izin_periode($request->bulan, $request->tahun);
            $cuti = $user->cuti_periode($request->bulan, $request->tahun);

            $gaji_pokok = 3000000;
            $transport = 100000;
            $makan = 100000;
            $lembur = $lembur_jam * 50000;
            $pot_telat = $telat_jam * 25000;
            $pot_izin = $izin * 50000;
            $pot_cuti = 0;
            $total = $gaji_pokok + $transport + $makan + $lembur - $pot_telat - $pot_izin;

            $karyawan = $user->karyawan; // pastikan relasi user->karyawan ada
            $slip = SlipGaji::updateOrCreate(
                [
                    'karyawan_id' => $karyawan->id,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                ],
                [
                    'total_lembur_jam' => $lembur_jam,
                    'total_telat_jam' => $telat_jam,
                    'potongan' => $pot_telat + $pot_izin,
                    'total_gaji' => $total,
                    'status' => 'dibayar',
                ]
            );

            DB::commit();
            return response()->json(['success' => true, 'slip' => $slip]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 3. Proses gaji semua karyawan
    public function prosesSemua(Request $request)
    {
        DB::beginTransaction();
        try {
            $users = User::where('role', 'karyawan')->get();
            foreach ($users as $user) {
                $karyawan = $user->karyawan;
                if (!$karyawan) continue;
                $lembur_jam = $user->lembur_jam_periode($request->bulan, $request->tahun);
                $telat_jam = $user->telat_jam_periode($request->bulan, $request->tahun);
                $izin = $user->izin_periode($request->bulan, $request->tahun);
                $cuti = $user->cuti_periode($request->bulan, $request->tahun);
                $gaji_pokok = 3000000;
                $transport = 100000;
                $makan = 100000;
                $lembur = $lembur_jam * 50000;
                $pot_telat = $telat_jam * 25000;
                $pot_izin = $izin * 50000;
                $pot_cuti = 0;
                $total = $gaji_pokok + $transport + $makan + $lembur - $pot_telat - $pot_izin;
                SlipGaji::updateOrCreate(
                    [
                        'karyawan_id' => $karyawan->id,
                        'bulan' => $request->bulan,
                        'tahun' => $request->tahun,
                    ],
                    [
                        'total_lembur_jam' => $lembur_jam,
                        'total_telat_jam' => $telat_jam,
                        'potongan' => $pot_telat + $pot_izin,
                        'total_gaji' => $total,
                        'status' => 'dibayar',
                    ]
                );
            }
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
