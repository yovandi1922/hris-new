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
        'name',
        'email',
        'password',
        'role', // admin / user
    ];

    // Kolom yang disembunyikan saat response JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];
}

