<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{

     protected $fillable = ['field_id','name','price_per_hour'];

    public function teacherSubject() {
        return $this->hasMany(TeacherSubject::class);
    }
}
