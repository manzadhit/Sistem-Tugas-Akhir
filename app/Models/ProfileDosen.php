<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ProfileDosen extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'profile_dosen';

    protected $fillable = [
        'user_id',
        'nidn',
        'nama_lengkap',
        'jurusan',
        'keahlian',
        'jabatan_fungsional',
        'sinta_score_3y',
        'status',
        'foto',
        'no_telp',
    ];

    protected $casts = [
        'sinta_score_3y' => 'float',
    ];

    protected $appends = [
        'initials',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pembimbingMahasiswa()
    {
        return $this->hasMany(DosenPembimbing::class, 'dosen_id');
    }

    public function pengujiMahasiswa()
    {
        return $this->hasMany(DosenPenguji::class, 'dosen_id');
    }

    public function publikasi()
    {
        return $this->hasMany(PublikasiDosen::class, 'dosen_id');
    }

    public function mataKuliah()
    {
        return $this->belongsToMany(
            MataKuliah::class,
            'dosen_mata_kuliah',
            'dosen_id',
            'mata_kuliah_id'
        )->withTimestamps();
    }

    public function getInitialsAttribute()
    {
        return substr(collect(explode(' ', $this->nama_lengkap))
            ->map(fn ($w) => strtoupper($w[0]))
            ->implode(''), 0, 2);
    }
}
