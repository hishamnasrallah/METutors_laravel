<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAssignment extends Model
{
    use HasFactory;

    public function assignment(){
        return $this->belongsTo(Assignment::class,'assignment_id','id');
    }

    public function assignee(){
        return $this->hasMany(User::class,'id','user_id');
    }

    public function courses(){
        return $this->hasMany(Course::class,'id','course_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
