<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Absen;
use App\Models\Pengajuan;
use App\Models\Cuti; // opsional, kalau ada tabel cuti
use Carbon\Carbon;

class AdminController extends Controller
{
  
    public function dashboard()
    {
        // ========== SETUP WAKTU ==========
        $now = Carbon::now('Asia/Jakarta');
        $loggedUser = Auth::user();

        // ========== HITUNG RANGE MINGGU KERJA (SENIN - JUMAT) ==========
        // Jika hari ini Senin, ambil dari Senin kemarin (minggu baru reset)
        // Jika hari ini Selasa-Jumat, ambil dari Senin minggu ini
        // Jika hari ini Sabtu-Minggu, ambil dari Senin minggu sebelumnya
        
        $currentDayOfWeek = $now->dayOfWeek; // 0=Minggu, 1=Senin, ..., 6=Sabtu

        if ($currentDayOfWeek == 0 || $currentDayOfWeek == 6) {
            // Jika Sabtu atau Minggu, ambil Senin-Jumat minggu sebelumnya
            $startOfWeek = $now->copy()->subWeeks(1)->startOfWeek(Carbon::MONDAY);
            $endOfWeek = $startOfWeek->copy()->addDays(4); // Jumat
        } else {
            // Jika Senin-Jumat, ambil Senin minggu ini
            $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = $now->copy()->subDays($currentDayOfWeek - 1)->addDays(4); // Jumat minggu ini
        }

        // ========== TOTAL KARYAWAN AKTIF ==========
        $totalKaryawan = User::where('role', 'karyawan')->count();

        // ========== ABSENSI HARI INI ==========
        $today = $now->toDateString();
        $sudahAbsenHariIni = Absen::whereDate('tanggal', $today)
            ->distinct('user_id')
            ->count('user_id');
        $belumAbsenHariIni = max(0, $totalKaryawan - $sudahAbsenHariIni);

        // ========== GRAFIK ABSENSI PER HARI (SENIN-JUMAT) ==========
        // Hitung jumlah unik karyawan yang absen per hari dalam minggu kerja
        $absensiHarian = $this->getAbsensiPerHari($startOfWeek, $endOfWeek);

        // ========== DONUT CHART: SUDAH ABSEN VS BELUM HARI INI ==========
        // Hanya hari kerja (Senin-Jumat)
        if ($currentDayOfWeek >= 1 && $currentDayOfWeek <= 5) {
            // Hari kerja
            $sudahAbsen = $sudahAbsenHariIni;
            $belumAbsen = $belumAbsenHariIni;
        } else {
            // Jika hari Sabtu/Minggu, tampilkan 0 (tidak ada shift kerja)
            $sudahAbsen = 0;
            $belumAbsen = $totalKaryawan;
        }

        // ========== DATA CUTI PER HARI DALAM MINGGU KERJA ==========
        $cuti = $this->getCutiPerHari($startOfWeek, $endOfWeek);

        // ========== FORMAT WAKTU & TANGGAL ==========
        $daysIndo = [
            'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat',
            'Sabtu', 'Minggu'
        ];
        
        $currentTime = $now->format('H:i');
        $currentDayEn = $now->format('l');
        $currentDay = $daysIndo[$currentDayOfWeek] ?? $currentDayEn;
        $currentDate = $now->locale('id_ID')->translatedFormat('d F Y');

        // ========== GREETING SESUAI JAM ==========
        $hour = (int) $now->format('H');
        $greeting = match(true) {
            $hour >= 5 && $hour < 12 => 'Selamat pagi',
            $hour >= 12 && $hour < 17 => 'Selamat siang',
            $hour >= 17 && $hour < 20 => 'Selamat sore',
            default => 'Selamat malam'
        };

        // ========== SISTEM NOTIFIKASI OTOMATIS ==========
        $notifikasi = [];

        // 1️⃣ Alert: Karyawan belum absen hari ini (hanya hari kerja)
        if ($currentDayOfWeek >= 1 && $currentDayOfWeek <= 5 && $belumAbsenHariIni > 0) {
            $notifikasi[] = [
                'icon' => '✖',
                'color' => 'red',
                'judul' => 'Absensi',
                'pesan' => "{$belumAbsenHariIni} karyawan belum absen hari ini.",
                'waktu' => $currentTime,
            ];
        }

        // 2️⃣ Info: Karyawan baru ditambahkan hari ini
        $karyawanBaru = User::where('role', 'karyawan')
            ->whereDate('created_at', $today)
            ->count();

        if ($karyawanBaru > 0) {
            $notifikasi[] = [
                'icon' => '👤',
                'color' => 'green',
                'judul' => 'Karyawan Baru',
                'pesan' => "{$karyawanBaru} karyawan baru ditambahkan hari ini.",
                'waktu' => $currentTime,
            ];
        }

        // 3️⃣ Info: Ada pengajuan cuti/lembur menunggu
        $pengajuanMenunggu = Pengajuan::where('status', 'pending')
            ->count();

        if ($pengajuanMenunggu > 0) {
            $notifikasi[] = [
                'icon' => '📋',
                'color' => 'yellow',
                'judul' => 'Pengajuan',
                'pesan' => "{$pengajuanMenunggu} pengajuan menunggu persetujuan.",
                'waktu' => $currentTime,
            ];
        }

        // ========== KIRIM KE VIEW ==========
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

    /**
     * Helper: Hitung absensi per hari (Senin-Jumat)
     * Return: Collection dengan index 0-4 untuk [Senin, Selasa, Rabu, Kamis, Jumat]
     */
    private function getAbsensiPerHari($startOfWeek, $endOfWeek)
    {
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $daysAbbrev = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'];

        // Query: Hitung DISTINCT user_id per hari
        $rawAbsensi = Absen::selectRaw("DAYNAME(tanggal) as day_name, COUNT(DISTINCT user_id) as count")
            ->whereBetween('tanggal', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->groupBy('day_name')
            ->pluck('count', 'day_name');

        // Map ke hari kerja (Senin-Jumat) dengan nilai default 0
        $result = collect();
        foreach ($daysOfWeek as $dayName) {
            $result->push((int) ($rawAbsensi->get($dayName, 0) ?? 0));
        }

        return $result;
    }

    /**
     * Helper: Hitung cuti per hari (Senin-Jumat)
     * Return: Collection dengan index 0-4 untuk [Senin, Selasa, Rabu, Kamis, Jumat]
     */
    private function getCutiPerHari($startOfWeek, $endOfWeek)
    {
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        // Query: Hitung pengajuan dengan jenis 'cuti' per hari
        $rawCuti = Pengajuan::selectRaw("DAYNAME(tanggal_mulai) as day_name, COUNT(*) as count")
            ->where('jenis', 'cuti') // Filter hanya type 'cuti'
            ->where('status', 'acc') // Hanya yang sudah disetujui (acc = approved)
            ->whereBetween('tanggal_mulai', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->groupBy('day_name')
            ->pluck('count', 'day_name');

        // Map ke hari kerja (Senin-Jumat) dengan nilai default 0
        $result = collect();
        foreach ($daysOfWeek as $dayName) {
            $result->push((int) ($rawCuti->get($dayName, 0) ?? 0));
        }

        return $result;
    }



    public function karyawan(Request $request)
    {
        $search = $request->input('search');
        $tahun = $request->input('tahun');
        $tahunMulai = $request->input('tahun_mulai');
        $tahunSelesai = $request->input('tahun_selesai');
        $jabatan = $request->input('jabatan');
        $status = $request->input('status');

        // Tampilkan semua user kecuali login user sendiri
        $employees = User::whereIn('role', ['karyawan', 'admin'])
            ->where('id', '!=', auth()->id())
            // Filter pencarian nama/email
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            // Filter tahun gabung spesifik
            ->when($tahun, function ($query, $tahun) {
                $query->whereYear('start_date', $tahun);
            })
            // Filter rentang tahun
            ->when($tahunMulai && $tahunSelesai, function ($query) use ($tahunMulai, $tahunSelesai) {
                $startDate = $tahunMulai . '-01-01';
                $endDate = $tahunSelesai . '-12-31';
                $query->whereBetween('start_date', [$startDate, $endDate]);
            })
            // Filter jabatan
            ->when($jabatan, function ($query, $jabatan) {
                $query->where('role', $jabatan);
            })
            // Filter status kerja
            ->when($status, function ($query, $status) {
                $query->where('work_status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.karyawan.index', compact('employees'));
    }

    public function absensi(Request $request)
    {
        $search = $request->input('search');
        $periode = $request->input('periode', []);
        $daterange = $request->input('daterange');
        $start_date = null;
        $end_date = null;
        if ($daterange && strpos($daterange, ' - ') !== false) {
            [$start_date, $end_date] = explode(' - ', $daterange);
        }
        $status = $request->input('status', []);
        $keterangan = $request->input('keterangan', []);

        $absensi = Absen::with('user')
            ->when($search, function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('nip', 'like', "%$search%") ;
                });
            })
            // Filter periode (bisa multi)
            ->when(in_array('today', (array)$periode), function($query) {
                $query->orWhereDate('tanggal', now()->toDateString());
            })
            ->when(in_array('yesterday', (array)$periode), function($query) {
                $query->orWhereDate('tanggal', now()->subDay()->toDateString());
            })
            ->when(in_array('this_week', (array)$periode), function($query) {
                $query->orWhereBetween('tanggal', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()]);
            })
            ->when(in_array('this_month', (array)$periode), function($query) {
                $query->orWhereMonth('tanggal', now()->month)->orWhereYear('tanggal', now()->year);
            })
            ->when(in_array('range', (array)$periode) && $start_date && $end_date, function($query) use ($start_date, $end_date) {
                $query->orWhereBetween('tanggal', [$start_date, $end_date]);
            })
            // Filter status clock-in (bisa multi)
            ->when(in_array('clockin', (array)$status), function($query) {
                $query->whereNotNull('jam_masuk');
            })
            ->when(in_array('noclockin', (array)$status), function($query) {
                $query->orWhereNull('jam_masuk');
            })
            // Filter keterangan (bisa multi)
            ->when(in_array('tepat', (array)$keterangan), function($query) {
                $query->where('jam_masuk', '<=', '08:00:00');
            })
            ->when(in_array('terlambat', (array)$keterangan), function($query) {
                $query->orWhere('jam_masuk', '>', '08:00:00');
            })
            ->when(in_array('lembur', (array)$keterangan), function($query) {
                $query->orWhere('jam_keluar', '>', '16:00:00');
            })
            ->latest()
            ->get();
        return view('admin.absen', compact('absensi', 'search'));
    }

    public function createKaryawan(Request $request)
    {
        // Ambil NIP terbesar, default 001 jika belum ada
        $nipTerbesar = \App\Models\User::max('nip');
        $nipBaru = $nipTerbesar ? str_pad(((int)$nipTerbesar) + 1, 3, '0', STR_PAD_LEFT) : '001';
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['nipBaru' => $nipBaru]);
        }
        return view('admin.karyawan-create', compact('nipBaru'));
    }

    public function storeKaryawan(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nip' => 'required|digits:3|unique:users,nip',
            'name' => 'required|string|max:100',
            'position' => 'required|in:Staf,Admin',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone' => 'required|regex:/^[0-9]{10,}$/',
            'start_date' => 'required|date',
        ]);

        try {
            // Tentukan role berdasarkan position
            $role = $validated['position'] === 'Admin' ? 'admin' : 'karyawan';

            // Buat user baru
            $employee = User::create([
                'nip' => $validated['nip'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $role,
                'phone' => $validated['phone'],
                'start_date' => $validated['start_date'],
            ]);

            // Jika request dari AJAX, return JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Karyawan berhasil ditambahkan',
                    'employee' => $employee
                ], 201);
            }

            // Jika form biasa, redirect
            return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 422);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function editKaryawan($id)
    {
        $loggedUser = Auth::user();
        $karyawan = User::findOrFail($id);
        return view('admin.karyawan-edit', compact('karyawan', 'loggedUser'));
    }

    public function getKaryawan($id)
    {
        try {
            $employee = User::findOrFail($id);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'employee' => $employee
                ]);
            }

            return view('admin.karyawan.detail', compact('employee'));
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan tidak ditemukan'
                ], 404);
            }

            return back()->withErrors(['error' => 'Karyawan tidak ditemukan']);
        }
    }

    public function updateKaryawan(Request $request, $id)
    {
        $karyawan = User::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $karyawan->id,
            'start_date' => 'nullable|date',
            'role' => 'required|in:admin,karyawan',
            'work_status' => 'required|in:Aktif,Resign',
        ]);

        try {
            // Update data
            $karyawan->update($validated);

            // Jika AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data karyawan berhasil diperbarui',
                    'employee' => $karyawan
                ]);
            }

            // Jika form biasa
            return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 422);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

// Daftar semua lembur
// Daftar semua karyawan
// public function daftarKaryawan() {
//     $karyawans = User::all();
//     return view('admin.lembur.daftar', compact('karyawans'));
// }

// Detail lembur per karyawan
public function detailLembur($id)
{
    $karyawan = User::findOrFail($id);

    $absenKaryawan = Absen::where('user_id', $id)
    ->whereNotNull('jam_keluar')
    ->whereTime('jam_keluar', '>', '16:00')
    ->get()
        ->map(function($a){
            $jamSelesai = \Carbon\Carbon::parse('16:00');
            $jamKeluar = \Carbon\Carbon::parse($a->jam_keluar);
            $lemburMenit = $jamKeluar->diffInMinutes($jamSelesai);

            return [
                'tanggal' => $a->tanggal,
                'jam_masuk' => $a->jam_masuk,
                'jam_keluar' => $a->jam_keluar,
                'durasi_lembur' => $lemburMenit,
            ];
        });

    return view('admin.lembur.detail', compact('karyawan', 'absenKaryawan'));
}



}
