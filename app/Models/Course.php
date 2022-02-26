<?php

namespace App\Models;

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

}
