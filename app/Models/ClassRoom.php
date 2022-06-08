<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id', 'id')->select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function classes()
    {
        return $this->hasMany(AcademicClass::class, 'course_id', 'course_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id')->select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }
}
