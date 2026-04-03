<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'profile_mahasiswa';

    protected $fillable = [
        'user_id',
        'nim',
        'nama_lengkap',
        'jurusan',
        'angkatan',
        'ipk',
        'no_telp',
        'foto',
        'status_akademik',
    ];

    protected $casts = [
        'ipk' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tugasAkhir()
    {
        return $this->hasOne(TugasAkhir::class, 'mahasiswa_id');
    }

    public function dosenPembimbing()
    {
        return $this->hasMany(DosenPembimbing::class, 'mahasiswa_id');
    }

    public function dosenPenguji()
    {
        return $this->hasMany(DosenPenguji::class, 'mahasiswa_id');
    }

    public function permintaanPembimbing()
    {
        return $this->hasOne(PermintaanPembimbing::class, 'mahasiswa_id');
    }
}
