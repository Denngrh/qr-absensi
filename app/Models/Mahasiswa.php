<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'jurusan',
        'semester',
        'email',
        'no_hp',
        'qr_code',
    ];

    public function absensis()
    {
        return $this->morphMany(Absensi::class, 'participant');
    }
}
