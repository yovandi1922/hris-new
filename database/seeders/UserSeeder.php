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
        // Buat atau update akun Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // cari berdasarkan email
            [
                'name' => 'Admin',
                'nip' => '001',
                'jabatan' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Buat atau update akun User biasa
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'yovandi',
                'nip' => '002',
                'jabatan' => 'Staff Keuangan',
                'password' => Hash::make('karyawan123'),
                'role' => 'karyawan',
            ]
        );
    }
}
