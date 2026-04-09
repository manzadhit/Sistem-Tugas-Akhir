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
        'penetapan_dilihat',
    ];

    protected $casts = [
        'diproses_pada' => 'datetime',
        'penetapan_dilihat' => 'boolean',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(ProfileMahasiswa::class, 'mahasiswa_id');
    }

    public function mataKuliah()
    {
        return $this->belongsToMany(
            MataKuliah::class,
            'permintaan_pembimbing_mata_kuliah',
            'pp_id',
            'mk_id'
        )->withTimestamps();
    }
}
