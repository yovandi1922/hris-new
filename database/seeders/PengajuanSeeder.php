<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengajuan;
use App\Models\User;
use Carbon\Carbon;

class PengajuanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'user@gmail.com')->first();
        if ($user) {
            // Data yang bisa dibatalkan (pending, tanggal mulai >= hari ini)
            Pengajuan::create([
                'user_id' => $user->id,
                'jenis' => 'Cuti Tahunan',
                'tanggal_mulai' => \Carbon\Carbon::today()->toDateString(),
                'tanggal_selesai' => \Carbon\Carbon::today()->addDays(2)->toDateString(),
                'durasi' => 3,
                'keterangan' => 'Test Batalkan',
                'bukti' => null,
                'status' => 'pending',
            ]);
            // Data yang tidak bisa dibatalkan
            Pengajuan::create([
                'user_id' => $user->id,
                'jenis' => 'Cuti Tahunan',
                'tanggal_mulai' => \Carbon\Carbon::today()->subDays(5)->toDateString(),
                'tanggal_selesai' => \Carbon\Carbon::today()->subDays(3)->toDateString(),
                'durasi' => 3,
                'keterangan' => 'Sudah Lewat',
                'bukti' => null,
                'status' => 'pending',
            ]);
            Pengajuan::create([
                'user_id' => $user->id,
                'jenis' => 'Cuti Tahunan',
                'tanggal_mulai' => \Carbon\Carbon::today()->toDateString(),
                'tanggal_selesai' => \Carbon\Carbon::today()->addDays(2)->toDateString(),
                'durasi' => 3,
                'keterangan' => 'Sudah Disetujui',
                'bukti' => null,
                'status' => 'acc',
            ]);
        }
    }
}
