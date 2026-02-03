<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\BonGaji;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\Pengajuan;
use App\Models\SlipGaji;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlipGajiController extends Controller
{
    private const GAJI_POKOK = 3000000;

    private const TARIF_LEMBUR_PER_JAM = 50000;

    private const POTONGAN_TELAT_PER_JAM = 25000;

    private const POTONGAN_IZIN = 25000;

    private const POTONGAN_TIDAK_MASUK = 100000;

    private const CICILAN_BON_PER_BULAN = 500000;

    // 1. Tampilkan halaman slip gaji admin
    public function index(Request $request)
    {
        $bulan = (int) $request->input('bulan', date('n'));
        $tahun = (int) $request->input('tahun', date('Y'));

        // Ambil semua karyawan
        $karyawanList = Karyawan::with('slipGajis')->get();

        // Ambil slip gaji periode (jika ada)
        $slipGajiPeriode = SlipGaji::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->with('karyawan')
            ->get();

        return view('admin.slip-gaji.index', [
            'karyawanList' => $karyawanList,
            'slipGajiPeriode' => $slipGajiPeriode,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);
    }

    // 2. Tampilkan detail perhitungan gaji (preview sebelum proses)
    public function detail($karyawan_id, Request $request)
    {
        try {
            $bulan = $request->input('bulan', date('n'));
            $tahun = $request->input('tahun', date('Y'));

            $karyawan = Karyawan::findOrFail($karyawan_id);
            $user = User::where('name', $karyawan->nama)->first();

            if (! $user) {
                return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
            }

            // Hitung gaji tanpa menyimpan
            $hasil = $this->hitungGaji($karyawan, $user, $bulan, $tahun);

            // Cek apakah sudah ada slip gaji
            $slipExist = SlipGaji::where('karyawan_id', $karyawan->id)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->first();

            return response()->json([
                'success' => true,
                'karyawan' => [
                    'id' => $karyawan->id,
                    'nip' => $karyawan->nip,
                    'nama' => $karyawan->nama,
                    'jabatan' => $karyawan->jabatan,
                ],
                'detail' => $hasil,
                'sudah_diproses' => $slipExist ? true : false,
                'slip_gaji' => $slipExist,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 3. Proses gaji satu karyawan (setelah konfirmasi)
    public function proses($karyawan_id, Request $request)
    {
        DB::beginTransaction();
        try {
            $bulan = $request->input('bulan', date('n'));
            $tahun = $request->input('tahun', date('Y'));

            $karyawan = Karyawan::findOrFail($karyawan_id);
            $user = User::where('name', $karyawan->nama)->first();

            if (! $user) {
                return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
            }

            $hasil = $this->hitungGaji($karyawan, $user, $bulan, $tahun);

            $slip = SlipGaji::updateOrCreate(
                [
                    'karyawan_id' => $karyawan->id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ],
                [
                    'total_lembur_jam' => $hasil['total_lembur_jam'],
                    'total_telat_jam' => $hasil['total_telat_jam'],
                    'potongan' => $hasil['total_potongan'],
                    'total_gaji' => $hasil['total_gaji'],
                    'status' => 'dibayar',
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gaji berhasil diproses',
                'slip' => $slip,
                'detail' => $hasil,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 4. Proses gaji semua karyawan
    public function prosesSemua(Request $request)
    {
        DB::beginTransaction();
        try {
            $bulan = $request->input('bulan', date('n'));
            $tahun = $request->input('tahun', date('Y'));

            $karyawans = Karyawan::all();
            $processed = 0;

            foreach ($karyawans as $karyawan) {
                $user = User::where('name', $karyawan->nama)->first();

                if (! $user) {
                    continue;
                }

                $hasil = $this->hitungGaji($karyawan, $user, $bulan, $tahun);

                SlipGaji::updateOrCreate(
                    [
                        'karyawan_id' => $karyawan->id,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                    ],
                    [
                        'total_lembur_jam' => $hasil['total_lembur_jam'],
                        'total_telat_jam' => $hasil['total_telat_jam'],
                        'potongan' => $hasil['total_potongan'],
                        'total_gaji' => $hasil['total_gaji'],
                        'status' => 'dibayar',
                    ]
                );

                $processed++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil memproses {$processed} karyawan",
                'processed' => $processed,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hitung gaji karyawan berdasarkan absensi, lembur, dan bon
     */
    private function hitungGaji(Karyawan $karyawan, User $user, int $bulan, int $tahun): array
    {
        // 1. Hitung lembur dari tabel lembur (status Disetujui)
        $totalLemburJam = Lembur::where('user_id', $user->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->where('status', 'Disetujui')
            ->get()
            ->sum(function ($lembur) {
                $start = \Carbon\Carbon::parse($lembur->jam_mulai);
                $end = \Carbon\Carbon::parse($lembur->jam_selesai);

                return $start->diffInMinutes($end) / 60;
            });

        // 2. Hitung telat dari tabel absensi (status hadir, jam_masuk > 08:00)
        $absensis = Absensi::where('karyawan_id', $karyawan->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get();

        $totalTelatJam = 0;
        foreach ($absensis as $absensi) {
            if ($absensi->status === 'hadir' && $absensi->jam_masuk) {
                $jamMasuk = \Carbon\Carbon::parse($absensi->tanggal.' '.$absensi->jam_masuk);
                $batasTelat = \Carbon\Carbon::parse($absensi->tanggal.' 08:00:00');

                if ($jamMasuk->greaterThan($batasTelat)) {
                    $telatMenit = $batasTelat->diffInMinutes($jamMasuk);
                    $totalTelatJam += $telatMenit / 60;
                }
            }
        }

        // 3. Hitung izin dari tabel pengajuan (jenis=izin, status=acc)
        $jumlahIzin = Pengajuan::where('user_id', $user->id)
            ->whereYear('tanggal_mulai', $tahun)
            ->whereMonth('tanggal_mulai', $bulan)
            ->where('status', 'acc')
            ->where('jenis', 'izin')
            ->sum('durasi'); // durasi dalam hari

        // 4. Hitung sakit (tidak masuk) dari tabel pengajuan (jenis=sakit, status=acc)
        $jumlahTidakMasuk = Pengajuan::where('user_id', $user->id)
            ->whereYear('tanggal_mulai', $tahun)
            ->whereMonth('tanggal_mulai', $bulan)
            ->where('status', 'acc')
            ->where('jenis', 'sakit')
            ->sum('durasi'); // durasi dalam hari

        // 5. Cuti TIDAK dipotong (sesuai aturan)

        // 6. Hitung bon gaji yang masih aktif (status disetujui)
        $totalCicilanBon = 0;
        $bonAktif = BonGaji::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->get();

        foreach ($bonAktif as $bon) {
            // Jika bon <= 500rb, potong langsung sesuai nilai bon (sekali lunas)
            // Jika bon > 500rb, potong 500rb per bulan (cicilan)
            if ($bon->jumlah <= self::CICILAN_BON_PER_BULAN) {
                $totalCicilanBon += $bon->jumlah;
            } else {
                $totalCicilanBon += self::CICILAN_BON_PER_BULAN;
            }
        }

        // 7. Hitung total
        $bonusLembur = $totalLemburJam * self::TARIF_LEMBUR_PER_JAM;
        $potonganTelat = $totalTelatJam * self::POTONGAN_TELAT_PER_JAM;
        $potonganIzin = $jumlahIzin * self::POTONGAN_IZIN;
        $potonganTidakMasuk = $jumlahTidakMasuk * self::POTONGAN_TIDAK_MASUK;
        $totalPotongan = $potonganTelat + $potonganIzin + $potonganTidakMasuk + $totalCicilanBon;

        $totalGaji = self::GAJI_POKOK + $bonusLembur - $totalPotongan;

        return [
            'gaji_pokok' => self::GAJI_POKOK,
            'total_lembur_jam' => round($totalLemburJam, 2),
            'total_lembur' => $bonusLembur,
            'total_telat_jam' => round($totalTelatJam, 2),
            'total_telat' => $potonganTelat,
            'jumlah_izin' => $jumlahIzin,
            'total_izin' => $potonganIzin,
            'jumlah_tidak_masuk' => $jumlahTidakMasuk,
            'total_tidak_masuk' => $potonganTidakMasuk,
            'total_bon' => $totalCicilanBon,
            'total_potongan' => round($totalPotongan, 0),
            'total_gaji' => round($totalGaji, 0),
        ];
    }
}
