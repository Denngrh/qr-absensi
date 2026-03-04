<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panitia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'email',
        'no_hp',
        'qr_code',
    ];

    public function absensis()
    {
        return $this->morphMany(Absensi::class, 'participant');
    }
}
