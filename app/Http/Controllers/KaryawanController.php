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
    'jam'       => now()->format('H:i:s'), // isi supaya tidak error
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
}
