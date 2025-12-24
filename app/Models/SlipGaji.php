<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlipGaji extends Model
{
    use HasFactory;

    protected $table = 'slip_gajis';

    protected $fillable = [
        'karyawan_id',
        'bulan',
        'tahun',
        'total_lembur_jam',
        'total_telat_jam',
        'potongan',
        'total_gaji',
        'status',
    ];

    // Relasi ke model Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}