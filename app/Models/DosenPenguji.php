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
        'nilai',
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(ProfileMahasiswa::class, 'mahasiswa_id');
    }

    public function dosen() 
    {
        return $this->belongsTo(ProfileDosen::class, 'dosen_id');
    }

    public function getJenisPenguji()
    {
        return match ($this->jenis_penguji) {
            'penguji_1' => 'Penguji 1',
            'penguji_2' => 'Penguji 2',
            'penguji_3' => 'Penguji 3',
            default => 'Penguji',
        };
    }
}
