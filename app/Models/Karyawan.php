<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan di database.
     */
    protected $table = 'karyawans'; // pastikan sama dengan nama tabel di database

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'nama',
        'email',
        'jabatan',
        'departemen',
        'status',
        'tanggal_gabung',
        'gaji', // tambahkan jika kolom ini ada di tabel
    ];

    /**
     * Kolom yang dianggap sebagai tanggal (otomatis jadi instance Carbon).
     */
    protected $dates = [
        'tanggal_gabung',
        'created_at',
        'updated_at',
    ];
}
