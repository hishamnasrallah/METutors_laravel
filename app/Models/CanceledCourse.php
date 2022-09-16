<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanceledCourse extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'cancelled_by', 'id')->select('id','id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function cancelled_by()
    {
        return $this->belongsTo(User::class, 'cancelled_by', 'id')->select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id')->select('id','id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id')->select('id', 'id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function classes()
    {
        return $this->hasMany(CanceledClass::class, 'canceled_course_id', 'id');
    }
}
