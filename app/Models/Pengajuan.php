<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';
    protected $fillable = [
    'user_id',
    'jenis',
    'tanggal',
    'jam_lembur',
    'nominal',
    'keterangan',
    'bukti',
    'status',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

