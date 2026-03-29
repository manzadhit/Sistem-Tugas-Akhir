<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenPenguji extends Model
{
    use HasFactory;

    protected $table = 'dosen_penguji';

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'jenis_penguji',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(ProfileMahasiswa::class, 'mahasiswa_id');
    }

    public function dosen() 
    {
        return $this->belongsTo(ProfileDosen::class, 'dosen_id');
    }
}
