<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KajurSubmissionFile extends Model
{
    protected $fillable = [
        'kajur_submission_id',
        'uploaded_by',
        'file_path'
    ];

    public function kajurSubmission()
    {
        return $this->belongsTo(KajurSubmission::class, 'kajur_submission_id');
    }
}
