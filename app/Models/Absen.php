<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    protected $table = 'absensi'; // kalau nama tabel kamu 'absensi'

    protected $fillable = [
    'user_id',
    'latitude',
    'longitude',
    'tanggal',
    'jam_masuk',
    'jam_keluar',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

