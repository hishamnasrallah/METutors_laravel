<?php

namespace App;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class FieldOfStudy extends Model
{
    public function teacherField() {
        return $this->hasMany(FieldOfStudy::class);
    }

    public function courses() {
        return $this->hasMany(Course::class,'field_of_study','id');
    }
}
