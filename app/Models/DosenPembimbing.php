<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenPembimbing extends Model
{
    use HasFactory;

    protected $table = 'dosen_pembimbing';

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'jenis_pembimbing',
        'status_aktif',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(ProfileMahasiswa::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(ProfileDosen::class, 'dosen_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'dosen_pembimbing_id');
    }

    public function getJenisPembimbing() {
        return match ($this->jenis_pembimbing) {
            'pembimbing_1' => 'Pembimbing 1',
            'pembimbing_2' => 'Pembimbing 2',
            default => 'Pembimbing'
        };
    }
}
