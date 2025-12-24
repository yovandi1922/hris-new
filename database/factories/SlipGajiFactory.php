<?php
namespace Database\Factories;

use App\Models\SlipGaji;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlipGajiFactory extends Factory
{
    protected $model = SlipGaji::class;

    public function definition()
    {
        return [
            // 'karyawan_id' => \App\Models\Karyawan::factory(), // aktifkan jika ingin relasi otomatis
            // Kolom lain diisi di seeder sesuai logic slip gaji
        ];
    }
}
