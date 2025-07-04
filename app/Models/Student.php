<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_id',
        'name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'program',
        'department',
        'enrollment_year',
    ];

    //
}
