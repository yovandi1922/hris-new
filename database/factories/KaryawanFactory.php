<?php

namespace Database\Factories;

use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Factories\Factory;

class KaryawanFactory extends Factory
{
    protected $model = Karyawan::class;

    public function definition(): array
    {
        $jabatanGaji = [
            'Staff Administrasi' => 3500000,
            'Staff Keuangan' => 4200000,
            'HR Staff' => 4000000,
            'IT Support' => 4500000,
            'Staff Operasional' => 3800000,
            'Marketing' => 3900000,
            'Customer Service' => 3600000,
        ];

        $jabatan = $this->faker->randomElement(array_keys($jabatanGaji));

        return [
            'nip' => 'KRY'.str_pad(fake()->unique()->numberBetween(1000, 9999), 5, '0', STR_PAD_LEFT),
            'nama' => $this->faker->name(),
            'jabatan' => $jabatan,
            'gaji_pokok' => $jabatanGaji[$jabatan],
        ];
    }
}
