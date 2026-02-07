<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;
    protected $fillable = ['nip','nama','jabatan','gaji_pokok'];
    public function absensis() { return $this->hasMany(Absensi::class); }
    public function slipGajis() { return $this->hasMany(SlipGaji::class); }
}
