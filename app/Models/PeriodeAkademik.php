<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeAkademik extends Model
{
    use HasFactory;

    protected $table = 'periode_akademik';

    protected $fillable = [
        'tahun_ajaran',
        'semester',
        'mulai_at',
        'selesai_at',
        'status',
    ];

    protected $casts = [
        'mulai_at' => 'date',
        'selesai_at' => 'date',
    ];

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function isAktif()
    {
        return $this->status === 'aktif';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isSelesai()
    {
        return $this->status === 'selesai';
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class, 'periode_akademik_id');
    }
}
