<?php

namespace Database\Factories;

use App\Models\Absensi;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsensiFactory extends Factory
{
    protected $model = Absensi::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement([
            'hadir' => 80,
            'izin' => 10,
            'sakit' => 5,
            'cuti' => 5,
        ]);

        // Jam masuk acak 07:45-08:30
        $jamMasukMenit = $this->faker->numberBetween(45, 90); // 07:45 = 45 menit dari 07:00, 08:30 = 90 menit
        $jamMasukJam = 7 + (int) ($jamMasukMenit / 60);
        $jamMasukMenit = $jamMasukMenit % 60;
        $jamMasuk = sprintf('%02d:%02d:00', $jamMasukJam, $jamMasukMenit);

        // Jam pulang acak 16:30-19:00
        $jamPulangMenit = $this->faker->numberBetween(990, 1140); // 16:30 = 990 menit dari 00:00, 19:00 = 1140 menit
        $jamPulangJam = (int) ($jamPulangMenit / 60);
        $jamPulangMenit = $jamPulangMenit % 60;
        $jamPulang = sprintf('%02d:%02d:00', $jamPulangJam, $jamPulangMenit);

        return [
            'tanggal' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'jam_masuk' => $status === 'hadir' ? $jamMasuk : null,
            'jam_pulang' => $status === 'hadir' ? $jamPulang : null,
            'status' => $status,
        ];
    }
}
