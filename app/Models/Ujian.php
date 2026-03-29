<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $table = 'ujian';

    protected $fillable = [
        'tugas_akhir_id',
        'periode_akademik_id',
        'jenis_ujian',
        'status',
        'catatan'
    ];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    public function periodeAkademik()
    {
        return $this->belongsTo(PeriodeAkademik::class, 'periode_akademik_id');
    }

    public function dokumenUjian()
    {
        return $this->hasMany(DokumenUjian::class, 'ujian_id');
    }

    public function jadwalUjian()
    {
        return $this->hasOne(JadwalUjian::class, 'ujian_id');
    }

    public function undanganUjian()
    {
        return $this->hasOne(UndanganUjian::class, 'ujian_id');
    }
}
