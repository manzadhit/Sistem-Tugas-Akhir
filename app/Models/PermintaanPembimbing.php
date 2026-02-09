<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanPembimbing extends Model
{
    use HasFactory;

    protected $table = 'permintaan_pembimbing';

    protected $fillable = [
        'mahasiswa_id',
        'judul_ta',
        'bukti_acc_path',
        'status',
        'status_verifikasi_bukti',
        'catatan',
        'diproses_pada',
    ];

    protected $casts = [
        'diproses_pada' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(ProfileMahasiswa::class, 'mahasiswa_id');
    }
}
