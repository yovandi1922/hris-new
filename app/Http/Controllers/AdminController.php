<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Absen;
use App\Models\Cuti; // opsional, kalau ada tabel cuti
use Carbon\Carbon;

class AdminController extends Controller
{
  
    public function dashboard()
{

    // Paksa timezone ke Jakarta
    $now = Carbon::now('Asia/Jakarta');
    $loggedUser = Auth::user();

    // === Total karyawan (role = 'karyawan') ===
    $totalKaryawan = User::where('role', 'karyawan')->count();

    // === Sudah absen hari ini ===
    $sudahAbsen = Absen::whereDate('tanggal', $now->toDateString())->distinct('user_id')->count('user_id');
    $belumAbsen = max(0, $totalKaryawan - $sudahAbsen);

    // === Absensi per minggu ===
    $startOfWeek = $now->copy()->startOfWeek(); 
    $endOfWeek   = $now->copy()->endOfWeek();

    $rawAbsensi = Absen::selectRaw("DAYNAME(tanggal) as hari_en, COUNT(*) as total")
        ->whereBetween('tanggal', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
        ->groupBy('hari_en')
        ->pluck('total', 'hari_en');

    $daysOrder = [
        'Monday'    => 'Sen',
        'Tuesday'   => 'Sel',
        'Wednesday' => 'Rab',
        'Thursday'  => 'Kam',
        'Friday'    => 'Jum'
    ];

    $absensiHarian = collect($daysOrder)->map(function ($shortName, $enName) use ($rawAbsensi) {
        return (int) $rawAbsensi->get($enName, 0);
    })->values();

    // === Data Cuti (jika modelnya ada) ===
    $cuti = collect([]);
    if (class_exists(\App\Models\Cuti::class)) {
        $rawCuti = \App\Models\Cuti::selectRaw("DAYNAME(created_at) as hari_en, COUNT(*) as total")
            ->whereBetween('created_at', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->groupBy('hari_en')
            ->pluck('total', 'hari_en');

        $cuti = collect($daysOrder)->map(function ($shortName, $enName) use ($rawCuti) {
            return (int) $rawCuti->get($enName, 0);
        })->values();
    }

    // === Format waktu dan tanggal ===
    $daysIndo = [
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
        'Sunday'    => 'Minggu'
    ];

    $currentTime = $now->format('H:i');
    $currentDayEn = $now->format('l');
    $currentDay = $daysIndo[$currentDayEn] ?? $currentDayEn;
    $currentDate = $now->translatedFormat('d F Y');

    // === Greeting sesuai jam ===
    $hour = (int) $now->format('H');
    if ($hour >= 5 && $hour < 12) {
        $greeting = 'Selamat pagi';
    } elseif ($hour >= 12 && $hour < 17) {
        $greeting = 'Selamat siang';
    } elseif ($hour >= 17 && $hour < 20) {
        $greeting = 'Selamat sore';
    } else {
        $greeting = 'Selamat malam';
    }

    // === 🔔 Sistem Notifikasi Otomatis ===
    $notifikasi = [];

    // 1️⃣ Belum absen hari ini
    if ($belumAbsen > 0) {
        $notifikasi[] = [
            'icon' => '✖',
            'color' => 'red',
            'judul' => 'Absen',
            'pesan' => "$belumAbsen karyawan belum melakukan absensi hari ini.",
            'waktu' => $currentTime,
        ];
    }

    // 2️⃣ Karyawan baru hari ini
    $karyawanBaru = User::where('role', 'karyawan')
        ->whereDate('created_at', $now->toDateString())
        ->get();

    if ($karyawanBaru->count() > 0) {
        $notifikasi[] = [
            'icon' => '👤',
            'color' => 'green',
            'judul' => 'Karyawan Baru',
            'pesan' => "{$karyawanBaru->count()} karyawan baru telah ditambahkan hari ini.",
            'waktu' => $currentTime,
        ];
    }

    // === Kirim ke view ===
    return view('admin.dashboard', [
        'loggedUser'     => $loggedUser,
        'totalKaryawan'  => $totalKaryawan,
        'sudahAbsen'     => $sudahAbsen,
        'belumAbsen'     => $belumAbsen,
        'absensiHarian'  => $absensiHarian,
        'cuti'           => $cuti,
        'currentTime'    => $currentTime,
        'currentDay'     => $currentDay,
        'currentDate'    => $currentDate,
        'greeting'       => $greeting,
        'notifikasi'     => $notifikasi,
    ]);
}



    public function karyawan(Request $request)
    {
        $search = $request->input('search');

        $employees = User::where('role', 'karyawan')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('admin.karyawan.index', compact('employees'));
    }

    public function absensi()
    {
        $absensi = Absen::with('user')->latest()->get();
        return view('admin.absen', compact('absensi'));
    }

    public function createKaryawan()
    {
        //
    }

    public function storeKaryawan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'karyawan',
        ]);

        return redirect()->route('admin.karyawan')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function editKaryawan($id)
    {
        $loggedUser = Auth::user();
        $karyawan = User::findOrFail($id);
        return view('admin.karyawan-edit', compact('karyawan', 'loggedUser'));
    }

    public function updateKaryawan(Request $request, $id)
    {
        $karyawan = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$karyawan->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $karyawan->name = $request->name;
        $karyawan->email = $request->email;
        if ($request->filled('password')) {
            $karyawan->password = Hash::make($request->password);
        }
        $karyawan->save();

        return redirect()->route('admin.karyawan')->with('success', 'Karyawan berhasil diperbarui');
    }

    public function destroyKaryawan($id)
    {
        $karyawan = User::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('admin.karyawan')->with('success', 'Karyawan berhasil dihapus');
    }
}
