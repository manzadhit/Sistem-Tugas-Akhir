<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KajurSubmission extends Model
{
    protected $fillable = [
        'tugas_akhir_id',
        'tahapan',
        'status',
        'catatan',
        'review'
    ];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    public function kajurSubmissionFiles()
    {
        return $this->hasMany(KajurSubmissionFile::class, 'kajur_submission_id');
    }
}
