<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenUjian extends Model
{
    protected $table = 'dokumen_ujian';
    
    protected $fillable = [
        'ujian_id',
        'kategori',
        'jenis_dokumen',
        'file_path',
        'status',
        'catatan'
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }
}
