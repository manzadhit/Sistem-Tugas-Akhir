<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Submission extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tugas_akhir_id',
        'tahapan',
        'dosen_pembimbing_id',
        'catatan',
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
