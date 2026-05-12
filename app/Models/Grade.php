<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id',
        'subject_id',
        'exam_type',
        'score',
        'max_score',
        'comments',
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
