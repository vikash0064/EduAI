<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id', 'student_profile_id', 'content', 
        'file_path', 'status', 'score', 'feedback', 'submitted_at'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
}
