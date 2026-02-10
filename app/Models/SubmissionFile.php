<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionFile extends Model
{
    protected $fillable = [
        'submission_id',
        'uploaded_by',
        'file_path'
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }
}
