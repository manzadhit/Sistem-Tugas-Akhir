<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode',
        'nama',
    ];

    public function permintaanPembimbing()
    {
        return $this->belongsToMany(
            PermintaanPembimbing::class,
            'permintaan_pembimbing_mata_kuliah',
            'mata_kuliah_id',
            'permintaan_pembimbing_id'
        );
    }
}
