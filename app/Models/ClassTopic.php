<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTopic extends Model
{
    use HasFactory;

    public function classes(){
        return $this->hasOne(AcademicClass::class,'id','class_id');
    }
}
