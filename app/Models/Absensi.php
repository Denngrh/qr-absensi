<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_type',
        'participant_id',
        'event_name',
        'event_date',
        'scan_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'scan_time' => 'datetime',
        'event_date' => 'date',
    ];

    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'participant_id');
    }

    public function panitia()
    {
        return $this->belongsTo(Panitia::class, 'participant_id');
    }

    public function getParticipantAttribute()
    {
        if ($this->participant_type === 'mahasiswa') {
            return $this->mahasiswa;
        }
        return $this->panitia;
    }
}
