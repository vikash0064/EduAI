<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'student_profile_id',
        'name',
        'type',
        'status',
        'file_path',
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
}
