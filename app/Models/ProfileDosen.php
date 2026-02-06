<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileDosen extends Model
{
    use HasFactory;
    
    protected $table = 'profile_dosen';

    protected $fillable = [
        'user_id',
        'nidn',
        'nama_lengkap',
        'jurusan',
        'program_studi',
        'keahlian',
        'foto',
        'no_telp'
    ];

    public function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
