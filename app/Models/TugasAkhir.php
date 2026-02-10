<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TugasAkhir extends Model
{
    use HasFactory;

    protected $table = 'tugas_akhir';

    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'abstrak',
        'tahapan',
        'file_path',
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(ProfileMahasiswa::class, 'mahasiswa_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'tugas_akhir_id');
    }
}
