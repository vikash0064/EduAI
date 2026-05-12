<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ParentStudent extends Pivot
{
    protected $table = 'parent_student';

    protected $fillable = [
        'parent_id',
        'student_profile_id',
    ];
}
