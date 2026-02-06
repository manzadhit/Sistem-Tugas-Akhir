<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'profile_mahasiswa';

    protected $fillable = [
        'user_id',
        'nim',
        'nama_lengkap',
        'jurusan',
        'program_studi',
        'angkatan',
        'ipk',
        'no_telp',
        'foto',
        'status_akademik',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
