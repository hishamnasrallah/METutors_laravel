<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
    
    public function classes(){
        return $this->hasMany(AcademicClass::class,'topic_id','id');
    }
}
