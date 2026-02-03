<?php

namespace Database\Factories;

use App\Models\SlipGaji;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlipGajiFactory extends Factory
{
    protected $model = SlipGaji::class;

    public function definition(): array
    {
        return [
            'bulan' => now()->month,
            'tahun' => now()->year,
            'total_lembur_jam' => 0,
            'total_telat_jam' => 0,
            'potongan' => 0,
            'total_gaji' => 0,
            'status' => 'dibayar',
        ];
    }
}
