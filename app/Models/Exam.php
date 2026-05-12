<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'subject_id', 'grade_level', 'section', 'date', 'room', 'status'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
