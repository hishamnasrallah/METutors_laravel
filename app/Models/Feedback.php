<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = ['name', 'role_name'];

    use HasFactory;

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id')->select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id')->select('id','id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile');
    }

    public function course()
    {
        return $this->belongsTo(Course::class)->select('id', 'course_code', 'created_at');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'feedback_by', 'id')->select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile');
    }
}
