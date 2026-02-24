<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UndanganUjian extends Model
{
    protected $table = 'undangan_ujian';

    protected $fillable = [
        'ujian_id',
        'nomor_surat',
        'hal',
        'tanggal_surat',
        'ketua_sidang_id',
        'sekretaris_sidang_id',
        'file_path',
        'status',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function ketuaSidang()
    {
        return $this->belongsTo(ProfileDosen::class, 'ketua_sidang_id');
    }

    public function sekretarisSidang()
    {
        return $this->belongsTo(ProfileDosen::class, 'sekretaris_sidang_id');
    }
}
