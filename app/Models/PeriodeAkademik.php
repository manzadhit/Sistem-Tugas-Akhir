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
        'is_active',
    ];

    protected $casts = [
        'mulai_at' => 'date',
        'selesai_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function dosenPenguji()
    {
        return $this->hasMany(DosenPenguji::class, 'periode_akademik_id');
    }
}
