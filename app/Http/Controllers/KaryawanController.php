<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absen;
use Carbon\Carbon;

class KaryawanController extends Controller
{
    // Dashboard karyawan (opsional: bisa tampilkan ringkasan hari ini)
    public function showDashboard()
    {
        $today = Carbon::today();
        $absenHariIni = Absen::where('user_id', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        return view('karyawan.index', compact('absenHariIni'));
    }

    // Halaman absen (pastikan route mengarah ke method ini)
    public function showAbsen()
    {
        $today = Carbon::today();
        $absenHariIni = Absen::where('user_id', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        return view('karyawan.absen', compact('absenHariIni'));
    }

    // Simpan absen masuk/keluar
    public function storeAbsen(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $userId = Auth::id();
        $today = now()->toDateString();

        $absenHariIni = Absen::where('user_id', $userId)
            ->where('tanggal', $today)
            ->first();

        if (!$absenHariIni) {
            // Absen masuk
            Absen::create([
                'user_id'   => $userId,
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
                'tanggal'   => $today,
                'jam'       => now()->format('H:i:s'),
                'jam_masuk' => now()->format('H:i:s'),
            ]);

            return redirect()->route('karyawan.index')->with('success', 'Absen masuk berhasil!');
        } elseif (is_null($absenHariIni->jam_keluar)) {
            // Absen keluar
            $absenHariIni->update([
                'jam_keluar' => now()->format('H:i:s'),
            ]);

            return redirect()->route('karyawan.absen')->with('success', 'Absen keluar berhasil!');
        }

        return redirect()->route('karyawan.absen')->with('info', 'Anda sudah absen masuk & keluar hari ini.');
    }

    /* ===============================================================
       CLOCK IN & CLOCK OUT TANPA GANGGU FITUR YANG SUDAH ADA
       =============================================================== */

    public function clockIn()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $absen = Absen::where('user_id', $user->id)
                      ->whereDate('tanggal', $today)
                      ->first();

        if ($absen) {
            return back()->with('info', 'Kamu sudah melakukan Clock In hari ini.');
        }

        Absen::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'jam_masuk' => Carbon::now()->format('H:i:s'),
        ]);

        return back()->with('success', 'Berhasil Clock In!');
    }

    // 🧠 1️⃣ Tambahan fungsi di bawah clockIn()
    public function checkClockInStatus()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $absen = Absen::where('user_id', $user->id)
                      ->whereDate('tanggal', $today)
                      ->first();

        if ($absen) {
            return response()->json([
                'status' => true,
                'message' => 'Sudah Clock In hari ini',
                'jam_masuk' => $absen->jam_masuk,
                'jam_keluar' => $absen->jam_keluar,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Belum Clock In hari ini',
            ]);
        }
    }

    public function clockOut()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $absen = Absen::where('user_id', $user->id)
                      ->whereDate('tanggal', $today)
                      ->first();

        if (!$absen) {
            return back()->with('error', 'Kamu belum Clock In hari ini.');
        }

        if ($absen->jam_keluar) {
            return back()->with('info', 'Kamu sudah melakukan Clock Out hari ini.');
        }

        $absen->update([
            'jam_keluar' => Carbon::now()->format('H:i:s'),
        ]);

        return back()->with('success', 'Berhasil Clock Out!');
    }

    /* ===============================================================
       HALAMAN DATA KARYAWAN
       =============================================================== */

    public function dataKaryawan()
    {
        $user = Auth::user(); // ambil data user yang sedang login
        return view('karyawan.data_karyawan', compact('user'));
    }
}
