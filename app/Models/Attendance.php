<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class,'user_id','id')->select('id','first_name','last_name','role_name','email','mobile','avatar');
    }
    public function class(){
        return $this->belongsTo(AcademicClass::class,'academic_class_id','id');
    }
}
