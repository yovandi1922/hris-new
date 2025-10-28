<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absen;
use Carbon\Carbon;

class KaryawanController extends Controller
{
    public function showDashboard()
    {
        $today = Carbon::today();
        $absenHariIni = Absen::where('user_id', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        return view('karyawan.index', compact('absenHariIni'));
    }

    // ✅ Halaman absen (sudah menampilkan aktivitas hari ini & sebelumnya)
    public function showAbsen()
    {
        $userId = Auth::id();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Ambil absen hari ini
        $absenHariIni = Absen::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->first();

        // Ambil absen hari sebelumnya
        $absenKemarin = Absen::where('user_id', $userId)
            ->whereDate('tanggal', $yesterday)
            ->first();

        // Hitung lembur (jika jam_keluar > 16:00)
        $jamLembur = 0;
        if ($absenHariIni && $absenHariIni->jam_keluar) {
            $jamKeluar = Carbon::parse($absenHariIni->jam_keluar);
            $batasLembur = Carbon::createFromTime(16, 0, 0);
            if ($jamKeluar->gt($batasLembur)) {
                $jamLembur = $jamKeluar->diffInHours($batasLembur);
            }
        }

        return view('karyawan.absen', compact('absenHariIni', 'absenKemarin', 'jamLembur'));
    }

    public function storeAbsen(Request $request)
{
    $request->validate([
        'latitude' => 'required',
        'longitude' => 'required',
    ]);

    // Lokasi kantor Neo Sangkal Putung
    $kantorLat = -7.5370577;
    $kantorLon = 110.7501636;
    $radius = 0.1; // 0.1 km = 100 meter

    // Hitung jarak antara user dan kantor
    $jarak = $this->hitungJarak($kantorLat, $kantorLon, $request->latitude, $request->longitude);

    if ($jarak > $radius) {
        return back()->with('error', 'Kamu berada di luar lokasi kantor (lebih dari 100 meter).');
    }

    $userId = Auth::id();
    $today = now()->toDateString();

    $absenHariIni = Absen::where('user_id', $userId)
        ->where('tanggal', $today)
        ->first();

    if (!$absenHariIni) {
        // Clock-in
        Absen::create([
            'user_id'   => $userId,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'tanggal'   => $today,
            'jam_masuk' => now()->format('H:i:s'),
        ]);

        return redirect()->route('karyawan.absen')->with('success', 'Absen masuk berhasil!');
    } elseif (is_null($absenHariIni->jam_keluar)) {
        // Clock-out
        $absenHariIni->update([
            'jam_keluar' => now()->format('H:i:s'),
        ]);

        return redirect()->route('karyawan.absen')->with('success', 'Absen keluar berhasil!');
    }

    return redirect()->route('karyawan.absen')->with('info', 'Anda sudah absen masuk & keluar hari ini.');
}



    // 🕒 Clock In
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

    // 🕓 Clock Out
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

    public function dataKaryawan()
    {
        $user = Auth::user();
        return view('karyawan.data_karyawan', compact('user'));
    }


    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
{
    $R = 6371; // radius bumi (km)
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $distance = $R * $c; // hasil dalam km
    return $distance;
}


}


