<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalUjian extends Model
{
    protected $table = 'jadwal_ujian';

    protected $fillable = [
        'ujian_id',
        'tanggal_ujian',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
    ];

    protected $casts = [
        'tanggal_ujian' => 'date',
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }
}
