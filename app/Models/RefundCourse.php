<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundCourse extends Model
{
    use HasFactory;

    public function student()
    {
        return $this->belongsTo(User::class,  'student_id', 'id')->select('id', 'id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class,  'teacher_id', 'id')->select('id', 'id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function refunded_classes()
    {
        return $this->hasMany(RefundClass::class);
    }

    public function canceled_course()
    {
        return $this->belongsTo(CanceledCourse::class, 'course_id', 'course_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'course_id', 'course_id');
    }
}
