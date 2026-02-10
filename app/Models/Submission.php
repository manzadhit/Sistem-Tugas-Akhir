<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    //
    protected $fillable = [
        'tugas_akhir_id',
        'dosen_pembimbing_id',
        'status',
        'review'
    ];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id');
    }

    public function dosenPembimbing()
    {
        return $this->belongsTo(DosenPembimbing::class, 'dosen_pembimbing_id');
    }

    public function submissionFiles()
    {
        return $this->hasMany(SubmissionFile::class, 'submission_id');
    }
}
