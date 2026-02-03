<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
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

    // Relasi ke karyawan (1 user = 1 karyawan berdasarkan nama)
    public function karyawan()
    {
        return $this->hasOne(\App\Models\Karyawan::class, 'nama', 'name');
    }

    // Relasi ke slip gaji melalui karyawan
    public function slipGajis()
    {
        return $this->hasManyThrough(
            \App\Models\SlipGaji::class,
            \App\Models\Karyawan::class,
            'nama', // Foreign key di karyawans
            'karyawan_id', // Foreign key di slip_gajis
            'name', // Local key di users
            'id' // Local key di karyawans
        );
    }
}

