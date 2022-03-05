<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFeedback extends Model
{
    use HasFactory;

    public function feedback(){
        return $this->belongsTo(Feedback::class,'feedback_id','id')->select('id','name');
    }

    public function sender(){
        return $this->belongsTo(User::class,'sender_id','id')->select('id','first_name','last_name','role_name','email','mobile','avatar');
    }

    public function teacher(){
        return $this->belongsTo(User::class,'teacher_id','id')->select('id','first_name','last_name','role_name','email','mobile');
    }

    public function course(){
        return $this->belongsTo(Course::class)->select('id','course_name','course_code','created_at');
    }

    public function user(){
        return $this->belongsTo(User::class,'feedback_by','id')->select('id','first_name','last_name','role_name','email','mobile');
    }
}
