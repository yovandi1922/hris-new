<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonGaji extends Model
{
    use HasFactory;

    protected $table = 'bon_gaji';

    protected $fillable = [
        'user_id',
        'jumlah',
        'keterangan',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
