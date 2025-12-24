<?php
namespace Database\Factories;
use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Factories\Factory;

class KaryawanFactory extends Factory
{
    protected $model = Karyawan::class;
    public function definition()
    {
        static $nip = 100;
        $jabatan = $this->faker->randomElement([
            'Staff Administrasi','Staff Keuangan','HR Staff','IT Support','Staff Operasional'
        ]);
        $gaji = [3200000, 3800000, 4000000, 4200000, 3100000, 3700000, 3000000][array_rand([0,1,2,3,4,5,6])];
        return [
            'nip' => $nip++,
            'nama' => $this->faker->name(),
            'jabatan' => $jabatan,
            'gaji_pokok' => $gaji,
            // 'user_id' => \App\Models\User::factory(), // aktifkan jika ada kolom user_id di tabel karyawans
        ];
    }
}
