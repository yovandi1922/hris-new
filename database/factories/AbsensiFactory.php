<?php
namespace Database\Factories;
use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsensiFactory extends Factory
{
    protected $model = Absensi::class;
    public function definition()
    {
        return [
            // 'karyawan_id' => \App\Models\Karyawan::factory(), // aktifkan jika ingin relasi otomatis
            'tanggal' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'jam_masuk' => $this->faker->dateTimeBetween('07:45:00', '08:30:00')->format('H:i:s'),
            'jam_pulang' => $this->faker->dateTimeBetween('16:30:00', '19:00:00')->format('H:i:s'),
            'status' => $this->faker->randomElement(['hadir','izin','sakit','cuti']),
        ];
    }
}
