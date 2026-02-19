<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $table = 'ujian';

    protected $fillable = [
        'tugas_akhir_id',
        'jenis_ujian',
        'status', 
        'catatan'
    ];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    public function dokumenUjian()
    {
        return $this->hasMany(DokumenUjian::class, 'ujian_id');
    }
}
