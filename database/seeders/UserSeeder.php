<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat akun Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'), // ganti sesuai kebutuhan
            'role' => 'admin',
        ]);

        // Buat akun User biasa
        User::create([
            'name' => 'yovandi',
            'email' => 'user@gmail.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
        ]);
    }
}
