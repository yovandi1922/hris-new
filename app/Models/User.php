<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Relasi ke karyawan (jika ada 1 user = 1 karyawan)
    public function karyawan()
    {
        return $this->hasOne(\App\Models\Karyawan::class, 'nama', 'name'); // atau sesuaikan jika ada kolom user_id
    }
    use HasFactory, Notifiable;

    // Tabel yang digunakan
    protected $table = 'users';

    // Kolom yang bisa diisi
    protected $fillable = [
        'nip',
        'name',
        'email',
        'password',
        'role', // admin / karyawan
        'phone',
        'start_date',
        'work_status', // Aktif / Resign
    ];

    // Kolom yang disembunyikan saat response JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];
}

