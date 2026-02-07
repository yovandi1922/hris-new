<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Absen;
use App\Models\User;
use App\Models\Pengajuan;

/**
 * ========================================
 * CONTOH IMPLEMENTASI & REFERENCE
 * ========================================
 * 
 * File ini berisi contoh-contoh implementasi
 * untuk Dashboard Controller dan related features
 */

/**
 * ========== 1. PERHITUNGAN RANGE MINGGU KERJA ==========
 * 
 * Logika:
 * - Senin-Jumat (1-5): Ambil Senin-Jumat minggu berjalan
 * - Sabtu-Minggu (6,0): Ambil Senin-Jumat minggu sebelumnya
 */
class WeekCalculationExample
{
    public static function getWorkWeekRange()
    {
        $now = Carbon::now('Asia/Jakarta');
        $dayOfWeek = $now->dayOfWeek; // 0=Sun, 1=Mon, ..., 6=Sat

        if ($dayOfWeek == 0 || $dayOfWeek == 6) {
            // Weekend: ambil minggu sebelumnya
            $startOfWeek = $now->copy()->subWeeks(1)->startOfWeek(Carbon::MONDAY);
            $endOfWeek = $startOfWeek->copy()->addDays(4); // Friday
        } else {
            // Weekday: ambil minggu ini
            $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = $now->copy()->endOfWeek(Carbon::SUNDAY)->subDays(2); // Friday
        }

        return [
            'start' => $startOfWeek,
            'end' => $endOfWeek,
            'dayOfWeek' => $dayOfWeek,
        ];
    }
}

/**
 * ========== 2. QUERY ABSENSI DENGAN DISTINCT USER ==========
 * 
 * Penting: Gunakan COUNT(DISTINCT user_id) untuk menghindari
 * double-counting jika ada multiple check-in per hari
 */
class AbsensiQueryExample
{
    /**
     * Hitung total karyawan yang absen per hari
     * Return: [Monday => 25, Tuesday => 28, ...]
     */
    public static function getAbsensiPerDay($startDate, $endDate)
    {
        return Absen::selectRaw("DAYNAME(tanggal) as day_name, COUNT(DISTINCT user_id) as total")
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('day_name')
            ->pluck('total', 'day_name');
    }

    /**
     * Hitung absensi hari ini (untuk donut chart)
     */
    public static function getTodayAttendance()
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        
        $totalEmployees = User::where('role', 'karyawan')->count();
        $presentEmployees = Absen::whereDate('tanggal', $today)
            ->distinct('user_id')
            ->count('user_id');
        
        return [
            'present' => $presentEmployees,
            'absent' => max(0, $totalEmployees - $presentEmployees),
            'total' => $totalEmployees,
        ];
    }

    /**
     * Hitung absensi dengan filter status
     * (Misal: hanya yang "hadir", exclude "sakit", "libur", dll)
     */
    public static function getAbsensiByStatus($status = 'hadir')
    {
        $now = Carbon::now('Asia/Jakarta');
        $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $startOfWeek->copy()->addDays(4);

        return Absen::selectRaw("DAYNAME(tanggal) as day_name, COUNT(DISTINCT user_id) as total")
            ->where('status', $status)
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->groupBy('day_name')
            ->pluck('total', 'day_name');
    }
}

/**
 * ========== 3. PENGAJUAN (CUTI / LEMBUR) ==========
 * 
 * Requirements:
 * - Hitung hanya yang "acc" (approved)
 * - Group by hari (Monday-Friday)
 * - Pisah per jenis (cuti vs lembur)
 */
class PengajuanQueryExample
{
    /**
     * Hitung cuti per hari dalam minggu kerja
     */
    public static function getCutiPerDay($startDate, $endDate)
    {
        return Pengajuan::selectRaw("DAYNAME(tanggal_mulai) as day_name, COUNT(*) as total")
            ->where('jenis', 'cuti')
            ->where('status', 'acc') // Hanya yang disetujui
            ->whereBetween('tanggal_mulai', [$startDate, $endDate])
            ->groupBy('day_name')
            ->pluck('total', 'day_name');
    }

    /**
     * Hitung lembur per hari
     */
    public static function getLemburPerDay($startDate, $endDate)
    {
        return Pengajuan::selectRaw("DAYNAME(tanggal) as day_name, COUNT(*) as total")
            ->where('jenis', 'lembur')
            ->where('status', 'acc')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('day_name')
            ->pluck('total', 'day_name');
    }

    /**
     * Hitung pengajuan menunggu (pending)
     */
    public static function getPendingCount()
    {
        return Pengajuan::where('status', 'pending')
            ->count();
    }

    /**
     * Hitung pengajuan yang ditolak (untuk notifikasi)
     */
    public static function getRejectedCount()
    {
        return Pengajuan::where('status', 'ditolak')
            ->whereDate('updated_at', Carbon::now('Asia/Jakarta')->toDateString())
            ->count();
    }
}

/**
 * ========== 4. MAPPING HARI DENGAN LABEL ==========
 * 
 * Penting untuk Chart.js dan display yang konsisten
 */
class DayMappingExample
{
    /**
     * Map hari dari database ke array untuk Chart.js
     * Input: ['Monday' => 25, 'Tuesday' => 28, ...]
     * Output: [25, 28, 24, 27, 20] (Mon-Fri)
     */
    public static function mapDaysToArray($dbResult)
    {
        $daysOrder = [
            'Monday' => 0,
            'Tuesday' => 1,
            'Wednesday' => 2,
            'Thursday' => 3,
            'Friday' => 4,
        ];

        $result = array_fill(0, 5, 0); // Default semua 0

        foreach ($dbResult as $day => $count) {
            if (isset($daysOrder[$day])) {
                $result[$daysOrder[$day]] = (int) $count;
            }
        }

        return $result;
    }

    /**
     * Alias dengan Collection (laravel way)
     */
    public static function mapDaysToCollection($dbPluckResult)
    {
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        return collect($daysOfWeek)->map(function ($day) use ($dbPluckResult) {
            return (int) ($dbPluckResult->get($day, 0) ?? 0);
        })->values();
    }
}

/**
 * ========== 5. NOTIFIKASI DINAMIS ==========
 * 
 * Sistem notifikasi yang bisa di-customize sesuai kebutuhan
 */
class NotificationExample
{
    public static function generateDashboardNotifications()
    {
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $currentTime = $now->format('H:i');
        $notifications = [];

        // 1. Check absensi
        $totalEmployees = User::where('role', 'karyawan')->count();
        $presentToday = Absen::whereDate('tanggal', $today)
            ->distinct('user_id')
            ->count('user_id');
        $absentCount = max(0, $totalEmployees - $presentToday);

        if ($absentCount > 0 && $now->isWeekday()) {
            $notifications[] = [
                'id' => 'absensi_' . time(),
                'icon' => '✖',
                'color' => 'red',
                'judul' => 'Absensi',
                'pesan' => "{$absentCount} karyawan belum absen hari ini.",
                'waktu' => $currentTime,
                'level' => 'warning',
            ];
        }

        // 2. Check pengajuan pending
        $pendingCount = Pengajuan::where('status', 'pending')->count();
        if ($pendingCount > 0) {
            $notifications[] = [
                'id' => 'pengajuan_' . time(),
                'icon' => '📋',
                'color' => 'yellow',
                'judul' => 'Pengajuan',
                'pesan' => "{$pendingCount} pengajuan menunggu approval.",
                'waktu' => $currentTime,
                'level' => 'info',
            ];
        }

        // 3. Check karyawan baru
        $newEmployeesToday = User::where('role', 'karyawan')
            ->whereDate('created_at', $today)
            ->count();
        
        if ($newEmployeesToday > 0) {
            $notifications[] = [
                'id' => 'karyawan_baru_' . time(),
                'icon' => '👤',
                'color' => 'green',
                'judul' => 'Karyawan Baru',
                'pesan' => "{$newEmployeesToday} karyawan baru ditambahkan hari ini.",
                'waktu' => $currentTime,
                'level' => 'success',
            ];
        }

        // 4. Check cuti yang dimulai hari ini
        $cutiHariIni = Pengajuan::where('jenis', 'cuti')
            ->where('status', 'acc')
            ->whereDate('tanggal_mulai', $today)
            ->count();

        if ($cutiHariIni > 0) {
            $notifications[] = [
                'id' => 'cuti_' . time(),
                'icon' => '🏖',
                'color' => 'blue',
                'judul' => 'Cuti',
                'pesan' => "{$cutiHariIni} karyawan mulai cuti hari ini.",
                'waktu' => $currentTime,
                'level' => 'info',
            ];
        }

        return $notifications;
    }
}

/**
 * ========== 6. HELPER UNTUK BLADE ==========
 * 
 * Helper functions yang bisa digunakan di blade template
 */
class BladeHelperExample
{
    /**
     * Format array untuk JSON di Blade
     * Usage: @json($absensiHarian->values())
     */
    public static function toChartData($collection)
    {
        return $collection->values()->toArray();
    }

    /**
     * Hitung persentase kehadiran
     */
    public static function getAttendancePercentage($present, $total)
    {
        if ($total == 0) return 0;
        return round(($present / $total) * 100, 2);
    }

    /**
     * Get status label dengan color
     */
    public static function getStatusBadge($status)
    {
        $badges = [
            'acc' => ['bg' => 'green', 'label' => 'Disetujui'],
            'pending' => ['bg' => 'yellow', 'label' => 'Menunggu'],
            'ditolak' => ['bg' => 'red', 'label' => 'Ditolak'],
        ];

        return $badges[$status] ?? $badges['pending'];
    }
}

/**
 * ========== USAGE EXAMPLE DI CONTROLLER ==========
 * 
 * // Setup
 * $week = WeekCalculationExample::getWorkWeekRange();
 * 
 * // Query
 * $absensiRaw = AbsensiQueryExample::getAbsensiPerDay($week['start'], $week['end']);
 * $absensiForChart = DayMappingExample::mapDaysToCollection($absensiRaw);
 * 
 * // Mapping
 * $todayAttendance = AbsensiQueryExample::getTodayAttendance();
 * 
 * // Notifications
 * $notifications = NotificationExample::generateDashboardNotifications();
 * 
 * // Return to view
 * return view('admin.dashboard', [
 *     'absensiHarian' => $absensiForChart,
 *     'sudahAbsen' => $todayAttendance['present'],
 *     'belumAbsen' => $todayAttendance['absent'],
 *     'notifikasi' => $notifications,
 * ]);
 */
