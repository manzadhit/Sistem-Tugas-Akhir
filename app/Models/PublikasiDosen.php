<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublikasiDosen extends Model
{
    use HasFactory;

    protected $table = 'publikasi_dosen';

    protected $fillable = [
        'dosen_id',
        'judul',
        'abstrak',
        'jenis_publikasi',
        'tahun',
        'penerbit',
        'url',
    ];

    protected $casts = [
        'tahun' => 'integer',
    ];

    /**
     * Relasi ke dosen pemilik publikasi.
     */
    public function dosen()
    {
        return $this->belongsTo(ProfileDosen::class, 'dosen_id');
    }
}
