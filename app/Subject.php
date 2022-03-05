<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public function teacherSubject() {
        return $this->hasMany(TeacherSubject::class);
    }
}
