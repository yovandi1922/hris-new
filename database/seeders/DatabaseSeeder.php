<?php

namespace Database\Seeders;

use App\Models\Absen;
use App\Models\Absensi;
use App\Models\BonGaji;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\Pengajuan;
use App\Models\SlipGaji;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    private const TARIF_LEMBUR_PER_JAM = 50000;

    private const TARIF_TELAT_PER_JAM = 25000;

    private const POTONGAN_IZIN = 50000;

    private const JAM_KERJA_NORMAL = 17; // 17:00

    private const JAM_TELAT_THRESHOLD = 8; // 08:00

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Memulai seeding data HRIS...');

        // 1. Generate 10 user (role: karyawan)
        $this->command->info('👤 Membuat 10 karyawan...');
        $users = User::factory(10)->create();

        // 2. Generate 10 karyawan terhubung ke user
        $karyawans = collect();
        $userKaryawanMap = []; // Map user_id ke karyawan_id
        foreach ($users as $user) {
            $karyawan = Karyawan::factory()->create([
                'nama' => $user->name,
            ]);
            $karyawans->push($karyawan);
            $userKaryawanMap[$user->id] = $karyawan->id;
        }
        $this->command->info('✅ 10 karyawan berhasil dibuat');

        // 3. Generate absensi 22 hari kerja untuk setiap karyawan
        $this->command->info('📅 Membuat data absensi 22 hari kerja...');
        $bulan = now()->month;
        $tahun = now()->year;

        foreach ($karyawans as $index => $karyawan) {
            $userId = $users[$index]->id;
            $this->generateAbsensi($karyawan, $userId, $bulan, $tahun);
        }
        $this->command->info('✅ Data absensi berhasil dibuat');

        // 4. Generate slip gaji otomatis dengan perhitungan
        $this->command->info('💰 Menghitung dan membuat slip gaji...');
        foreach ($karyawans as $karyawan) {
            $this->generateSlipGaji($karyawan, $bulan, $tahun);
        }
        $this->command->info('✅ Slip gaji berhasil dibuat');

        // 5. Generate pengajuan (cuti/izin)
        $this->command->info('📝 Membuat data pengajuan cuti/izin...');
        $adminUser = null; // Will be created later
        foreach ($users as $user) {
            $this->generatePengajuan($user, $bulan, $tahun);
        }
        $this->command->info('✅ Data pengajuan berhasil dibuat');

        // 6. Generate lembur
        $this->command->info('⏰ Membuat data lembur...');
        foreach ($users as $user) {
            $this->generateLembur($user, $bulan, $tahun);
        }
        $this->command->info('✅ Data lembur berhasil dibuat');

        // 7. Generate bon gaji
        $this->command->info('💵 Membuat data bon gaji...');
        foreach ($users as $user) {
            $this->generateBonGaji($user);
        }
        $this->command->info('✅ Data bon gaji berhasil dibuat');

        $this->command->info('🎉 Seeding selesai! Silakan login dan cek halaman slip gaji.');
    }

    /**
     * Generate absensi 22 hari kerja untuk karyawan
     */
    private function generateAbsensi(Karyawan $karyawan, int $userId, int $bulan, int $tahun): void
    {
        $absensiData = []; // Untuk tabel absensis (slip gaji)
        $absenData = []; // Untuk tabel absensi (fitur absen GPS)
        $hariKerja = 0;
        $tanggalMulai = Carbon::create($tahun, $bulan, 1);
        $tanggalAkhir = $tanggalMulai->copy()->endOfMonth();

        for ($tanggal = $tanggalMulai->copy(); $tanggal <= $tanggalAkhir; $tanggal->addDay()) {
            // Skip weekend (Sabtu=6, Minggu=0)
            if (in_array($tanggal->dayOfWeek, [0, 6])) {
                continue;
            }

            if ($hariKerja >= 22) {
                break;
            }

            $hariKerja++;

            // 80% hadir, 10% izin, 5% sakit, 5% cuti
            $rand = rand(1, 100);
            if ($rand <= 80) {
                $status = 'hadir';
            } elseif ($rand <= 90) {
                $status = 'izin';
            } elseif ($rand <= 95) {
                $status = 'sakit';
            } else {
                $status = 'cuti';
            }

            // Jam masuk acak 07:45 - 08:30
            $jamMasukMenit = rand(45, 90); // 07:45 hingga 08:30 dalam menit dari 07:00
            $jamMasukJam = 7 + (int) ($jamMasukMenit / 60);
            $jamMasukMenit = $jamMasukMenit % 60;
            $jamMasuk = sprintf('%02d:%02d:00', $jamMasukJam, $jamMasukMenit);

            // Jam pulang acak 16:30 - 19:00
            $jamPulangTotalMenit = rand(990, 1140); // 16:30=990 menit, 19:00=1140 menit dari 00:00
            $jamPulangJam = (int) ($jamPulangTotalMenit / 60);
            $jamPulangMenit = $jamPulangTotalMenit % 60;
            $jamPulang = sprintf('%02d:%02d:00', $jamPulangJam, $jamPulangMenit);

            // Data untuk tabel absensis (karyawan_id)
            $absensiData[] = [
                'karyawan_id' => $karyawan->id,
                'tanggal' => $tanggal->format('Y-m-d'),
                'jam_masuk' => $status === 'hadir' ? $jamMasuk : null,
                'jam_pulang' => $status === 'hadir' ? $jamPulang : null,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Data untuk tabel absensi (user_id dengan GPS)
            if ($status === 'hadir') {
                $absenData[] = [
                    'user_id' => $userId,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'jam_masuk' => $jamMasuk,
                    'jam_keluar' => $jamPulang,
                    'latitude' => fake()->latitude(-6.3, -6.1), // Jakarta area
                    'longitude' => fake()->longitude(106.7, 106.9),
                    'waktu_absen' => $tanggal->format('Y-m-d').' '.$jamMasuk,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Absensi::insert($absensiData);
        if (! empty($absenData)) {
            Absen::insert($absenData);
        }
    }

    /**
     * Generate slip gaji dengan perhitungan otomatis
     */
    private function generateSlipGaji(Karyawan $karyawan, int $bulan, int $tahun): void
    {
        // Ambil semua absensi karyawan di bulan ini
        $absensis = Absensi::where('karyawan_id', $karyawan->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get();

        $totalLemburJam = 0;
        $totalTelatJam = 0;
        $potongan = 0;

        foreach ($absensis as $absensi) {
            if ($absensi->status === 'hadir') {
                // Hitung lembur jika pulang > 17:00
                $jamPulang = Carbon::parse($absensi->tanggal.' '.$absensi->jam_pulang);
                $batasNormal = Carbon::parse($absensi->tanggal.' 17:00:00');

                if ($jamPulang->greaterThan($batasNormal)) {
                    $lemburMenit = $batasNormal->diffInMinutes($jamPulang); // Swap urutan
                    $lemburJam = $lemburMenit / 60;
                    $totalLemburJam += $lemburJam;
                }

                // Hitung telat jika masuk > 08:00
                $jamMasuk = Carbon::parse($absensi->tanggal.' '.$absensi->jam_masuk);
                $batasTelat = Carbon::parse($absensi->tanggal.' 08:00:00');

                if ($jamMasuk->greaterThan($batasTelat)) {
                    $telatMenit = $batasTelat->diffInMinutes($jamMasuk); // Swap urutan
                    $telatJam = $telatMenit / 60;
                    $totalTelatJam += $telatJam;
                }
            } elseif ($absensi->status === 'izin') {
                // Izin → potong 50.000
                $potongan += self::POTONGAN_IZIN;
            }
            // Cuti tidak dipotong
            // Sakit tidak dipotong (bisa disesuaikan)
        }

        // Hitung total potongan
        $potonganTelat = $totalTelatJam * self::TARIF_TELAT_PER_JAM;
        $totalPotongan = $potongan + $potonganTelat;

        // Hitung bonus lembur
        $bonusLembur = $totalLemburJam * self::TARIF_LEMBUR_PER_JAM;

        // Total gaji = gaji pokok + bonus lembur - potongan
        $totalGaji = $karyawan->gaji_pokok + $bonusLembur - $totalPotongan;

        // Buat slip gaji
        SlipGaji::create([
            'karyawan_id' => $karyawan->id,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'total_lembur_jam' => round($totalLemburJam, 2),
            'total_telat_jam' => round($totalTelatJam, 2),
            'potongan' => round($totalPotongan, 0),
            'total_gaji' => round($totalGaji, 0),
            'status' => 'dibayar',
        ]);
    }

    /**
     * Generate pengajuan cuti/izin untuk user
     */
    private function generatePengajuan(User $user, int $bulan, int $tahun): void
    {
        // Generate 2-4 pengajuan per user (mix cuti dan izin)
        $jumlahPengajuan = rand(2, 4);

        for ($i = 0; $i < $jumlahPengajuan; $i++) {
            $jenis = fake()->randomElement(['cuti', 'izin', 'sakit']);
            $durasi = $jenis === 'cuti' ? rand(2, 5) : rand(1, 2); // Cuti lebih lama

            // Tanggal random di bulan ini atau bulan lalu
            $tanggalMulai = Carbon::create($tahun, $bulan, 1)
                ->subMonths(rand(0, 1))
                ->addDays(rand(0, 20));

            $tanggalSelesai = $tanggalMulai->copy()->addDays($durasi - 1);

            // Status: 70% disetujui, 20% pending, 10% ditolak
            $rand = rand(1, 100);
            if ($rand <= 70) {
                $status = 'acc';
            } elseif ($rand <= 90) {
                $status = 'pending';
            } else {
                $status = 'ditolak';
            }

            $keteranganList = [
                'cuti' => ['Liburan keluarga', 'Acara keluarga', 'Keperluan pribadi', 'Cuti tahunan'],
                'izin' => ['Keperluan mendadak', 'Acara keluarga', 'Urusan penting', 'Keperluan keluarga'],
                'sakit' => ['Demam', 'Sakit kepala', 'Flu', 'Tidak enak badan', 'Sakit perut'],
            ];

            Pengajuan::create([
                'user_id' => $user->id,
                'jenis' => $jenis,
                'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
                'durasi' => $durasi,
                'keterangan' => fake()->randomElement($keteranganList[$jenis]),
                'bukti' => $jenis === 'sakit' ? 'surat_sakit_'.fake()->uuid().'.pdf' : null,
                'bukti_nama_asli' => $jenis === 'sakit' ? 'Surat Dokter.pdf' : null,
                'status' => $status,
                'created_at' => $tanggalMulai->copy()->subDays(rand(1, 7)),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Generate data lembur untuk user
     */
    private function generateLembur(User $user, int $bulan, int $tahun): void
    {
        // Generate 3-6 lembur per user
        $jumlahLembur = rand(3, 6);

        for ($i = 0; $i < $jumlahLembur; $i++) {
            // Tanggal lembur di bulan ini
            $tanggal = Carbon::create($tahun, $bulan, 1)->addDays(rand(0, 25));

            // Skip weekend
            if (in_array($tanggal->dayOfWeek, [0, 6])) {
                continue;
            }

            // Jam lembur mulai dari 17:00 - 19:00
            $jamMulaiMenit = rand(1020, 1140); // 17:00 - 19:00 dalam menit dari 00:00
            $jamMulaiJam = (int) ($jamMulaiMenit / 60);
            $jamMulaiMenit = $jamMulaiMenit % 60;
            $jamMulai = sprintf('%02d:%02d:00', $jamMulaiJam, $jamMulaiMenit);

            // Durasi lembur 1-4 jam
            $durasiJam = rand(1, 4);
            $jamSelesaiTotalMenit = ($jamMulaiJam * 60 + ($jamMulaiMenit % 60)) + ($durasiJam * 60);
            $jamSelesaiJam = (int) ($jamSelesaiTotalMenit / 60);
            $jamSelesaiMenit = $jamSelesaiTotalMenit % 60;
            $jamSelesai = sprintf('%02d:%02d:00', $jamSelesaiJam, $jamSelesaiMenit);

            // Status: 80% approved, 15% pending, 5% rejected
            $rand = rand(1, 100);
            if ($rand <= 80) {
                $status = 'Disetujui';
                $approvedBy = 1; // Admin ID (will be created)
                $approvedAt = $tanggal->copy()->addDays(1);
            } elseif ($rand <= 95) {
                $status = 'Menunggu';
                $approvedBy = null;
                $approvedAt = null;
            } else {
                $status = 'Ditolak';
                $approvedBy = 1;
                $approvedAt = $tanggal->copy()->addDays(1);
            }

            $keteranganList = [
                'Menyelesaikan laporan bulanan',
                'Deadline project urgent',
                'Meeting dengan klien',
                'Maintenance sistem',
                'Backup data',
                'Finishing project',
                'Persiapan presentasi',
                'Handle issue production',
            ];

            Lembur::create([
                'user_id' => $user->id,
                'tanggal' => $tanggal->format('Y-m-d'),
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'keterangan' => fake()->randomElement($keteranganList),
                'bukti' => 'lembur_'.fake()->uuid().'.jpg',
                'status' => $status,
                'approved_by' => $approvedBy,
                'approved_at' => $approvedAt,
                'created_at' => $tanggal->copy()->subHours(2),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Generate bon gaji untuk user
     */
    private function generateBonGaji(User $user): void
    {
        // Generate 1-3 bon gaji per user
        $jumlahBon = rand(1, 3);

        for ($i = 0; $i < $jumlahBon; $i++) {
            // Nominal bon: 100rb - 2jt
            $jumlah = rand(100000, 2000000);
            $jumlah = round($jumlah / 50000) * 50000; // Bulatkan ke 50rb

            // Status: 60% disetujui, 30% pending, 10% ditolak
            $rand = rand(1, 100);
            if ($rand <= 60) {
                $status = 'disetujui';
            } elseif ($rand <= 90) {
                $status = 'pending';
            } else {
                $status = 'ditolak';
            }

            $keteranganList = [
                'Keperluan mendesak',
                'Biaya pengobatan',
                'Keperluan keluarga',
                'Bayar cicilan',
                'Renovasi rumah',
                'Biaya pendidikan anak',
                'Keperluan darurat',
                'Modal usaha',
            ];

            BonGaji::create([
                'user_id' => $user->id,
                'jumlah' => $jumlah,
                'keterangan' => fake()->randomElement($keteranganList),
                'status' => $status,
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ]);
        }
    }
}
