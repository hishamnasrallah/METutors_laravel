<?php

namespace App\Models;

use App\Country;
use App\FieldOfStudy;
use App\Language;
use App\Program;
use App\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public function classes(){
        return $this->hasMany(AcademicClass::class);
    }

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function language(){
        return $this->belongsTo(Language::class);
    }

    public function program(){
        return $this->belongsTo(Program::class);
    }

    public function field(){
        return $this->belongsTo(FieldOfStudy::class,'field_of_study','id');
    }

    public function teacher(){
        return $this->belongsTo(User::class,'student_id','id')->select('id','first_name','last_name','role_name','email','mobile','avatar');

    }

    public function student(){
        return $this->belongsTo(User::class,'student_id','id')->select('id','first_name','last_name','role_name','email','mobile','avatar');
    }

    public function fields(){
        return $this->hasMany(FieldOfStudy::class,'field_of_study','id');
    }

    public function country(){
        return $this->belongsTo(Country::class)->select('id','name','emojiU');
    }

    public function resource(){
        return $this->belongsTo(Resource::class,'id','resource_id');
    }

    public function assignments(){
        return $this->hasMany(Assignment::class);
    }

    public function assignees(){
        return $this->hasMany(UserAssignment::class);
    }

    public function resnponse_recieved(){
        return $this->hasMany(Assignment::class);
    }

    public function feedbacks(){
        return $this->hasMany(UserFeedback::class,'course_id','id');
    }

    public function participants(){
        return $this->hasMany(ClassRoom::class,'course_id','id');
    }

   
}
