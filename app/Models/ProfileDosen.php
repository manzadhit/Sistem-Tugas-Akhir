<?php

namespace App\Models;

use App\Models\User;
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
        'program_studi',
        'keahlian',
        'jabatan_fungsional',
        'status',
        'kuota_pembimbing',
        'kuota_penguji',
        'foto',
        'no_telp'
    ];

    protected $casts = [
        'kuota_pembimbing' => 'integer',
        'kuota_penguji' => 'integer',
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
}
