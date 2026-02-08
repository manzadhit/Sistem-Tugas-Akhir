<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }


    public function profileMahasiswa()
    {
        return $this->hasOne(ProfileMahasiswa::class, 'user_id');
    }

    public function profileDosen()
    {
        return $this->hasOne(ProfileDosen::class, 'user_id');
    }

    public function getDisplayNameAttribute()
    {
        return match ($this->role) {
            'mahasiswa' => $this->profileMahasiswa?->nama_lengkap ?? $this->username,
            'dosen', 'kajur' => $this->profileDosen?->nama_lengkap ?? $this->username,
            'admin' => $this->username,
            default => $this->username,
        };
    }

    public function getDisplaySubtitleAttribute()
    {
        return match ($this->role) {
            'mahasiswa' => $this->profileMahasiswa?->nim,
            'dosen', 'kajur' => $this->profileDosen?->nidn,
            'admin' => $this->email,
            default => null,
        };
    }
}
