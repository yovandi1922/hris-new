<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lembur;
use App\Models\User;
use Carbon\Carbon;

class LemburSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'user@gmail.com')->first();
        if ($user) {
            Lembur::create([
                'user_id' => $user->id,
                'tanggal' => Carbon::today()->toDateString(),
                'jam_mulai' => '17:00:00',
                'jam_selesai' => '20:00:00',
                'keterangan' => 'Lembur audit produk',
                'bukti' => null,
                'status' => 'Menunggu',
            ]);
            Lembur::create([
                'user_id' => $user->id,
                'tanggal' => Carbon::yesterday()->toDateString(),
                'jam_mulai' => '18:00:00',
                'jam_selesai' => '21:00:00',
                'keterangan' => 'Lembur input data',
                'bukti' => null,
                'status' => 'Disetujui',
                'approved_by' => 1,
                'approved_at' => Carbon::yesterday()->addHours(4),
            ]);
            Lembur::create([
                'user_id' => $user->id,
                'tanggal' => Carbon::today()->subDays(2)->toDateString(),
                'jam_mulai' => '19:00:00',
                'jam_selesai' => '22:00:00',
                'keterangan' => 'Lembur revisi laporan',
                'bukti' => null,
                'status' => 'Ditolak',
                'approved_by' => 1,
                'approved_at' => Carbon::today()->subDays(2)->addHours(4),
            ]);
        }
    }
}
