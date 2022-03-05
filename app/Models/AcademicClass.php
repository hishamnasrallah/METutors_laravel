<?php

namespace App\Models;

use App\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicClass extends Model
{
    use HasFactory;

    public function classSessions(){
        return $this->hasMany(ClassSession::class);
    }

    public function student(){
        return $this->belongsTo(User::class,'student_id','id')->select('id','first_name','last_name','role_name','email','mobile');
    }

    public function teacher(){
        return $this->belongsTo(User::class,'teacher_id','id')->select('id','first_name','last_name','role_name','email','mobile');
    }

    public function course(){
        return $this->belongsTo(Course::class)->select('id','course_code','course_name','subject_id','created_at');
    }

    public function subject(){
        return $this->belongsTo(Subject::class);
    }
}
