<?php

namespace App\Models;

use App\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicClass extends Model
{
    // protected $fillable = ['academic_class_id'];   
    use HasFactory;

    public function classSessions(){
        return $this->hasMany(ClassSession::class);
    }

    public function student(){
        return $this->belongsTo(User::class,'student_id','id')->select('id','first_name','last_name','role_name','email','mobile','avatar');
    }

    public function teacher(){
        return $this->belongsTo(User::class,'teacher_id','id')->select('id','first_name','last_name','role_name','email','mobile','avatar');
    
    }
    
    public function course(){
        return $this->belongsTo(Course::class)->select('id','course_code','course_name','subject_id','created_at','student_id','program_id');
    }

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function attendence(){
        return $this->hasMany(Attendance::class);
    }

    public function resource(){
        return $this->belongsTo(Resource::class,'id','resource_id');
    }

    public function participants(){
        return $this->hasMany(ClassRoom::class,'course_id','course_id');
    }
    
}
