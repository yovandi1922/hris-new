<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\SlipGaji;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Generate 10 user (role: karyawan)
        // 1. Generate 10 user (role: karyawan)
        $users = \App\Models\User::factory(10)->create([
            'role' => 'karyawan',
        ]);

        // 2. Generate 10 karyawan terhubung ke user (jika ada kolom user_id di karyawans, aktifkan baris user_id)
        $karyawans = collect();
        foreach ($users as $user) {
            $karyawans->push(
                \App\Models\Karyawan::factory()->create([
                    // 'user_id' => $user->id, // aktifkan jika ada kolom user_id
                    'nama' => $user->name,
                ])
            );
        }

        // 3. Generate absensi 1 bulan penuh untuk setiap karyawan
        foreach ($karyawans as $karyawan) {
            $absensi = [];
            $bulan = now()->month;
            $tahun = now()->year;
            $hariKerja = 0;
            for ($d = 1; $d <= 31; $d++) {
                $tanggal = Carbon::create($tahun, $bulan, $d);
                if ($tanggal->month != $bulan) break;
                // Hanya Senin-Jumat (hari kerja)
                if (!in_array($tanggal->dayOfWeek, [1,2,3,4,5])) continue;
                if ($hariKerja >= 22) break;
                $hariKerja++;
                $status = fake()->randomElement(['hadir','hadir','hadir','izin','cuti']);
                $jamMasuk = $status=='hadir' ? fake()->dateTimeBetween($tanggal->format('Y-m-d').' 07:45:00', $tanggal->format('Y-m-d').' 08:30:00')->format('H:i:s') : null;
                $jamPulang = $status=='hadir' ? fake()->dateTimeBetween($tanggal->format('Y-m-d').' 16:30:00', $tanggal->format('Y-m-d').' 19:00:00')->format('H:i:s') : null;
                $absensi[] = [
                    'karyawan_id' => $karyawan->id,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'jam_masuk' => $jamMasuk,
                    'jam_pulang' => $jamPulang,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Absensi::insert($absensi);
        }

        // Tidak generate slip gaji otomatis di seeder. Slip gaji hanya dibuat saat proses gaji diklik di admin.
    }
}
