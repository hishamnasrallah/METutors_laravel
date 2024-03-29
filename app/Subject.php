<?php

namespace App;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{

    protected $fillable = ['program_id', 'country_id', 'grade', 'field_id', 'name','name_ar','title','title_ar','description','description_ar', 'price_per_hour', 'status'];

    public function teacherSubject()
    {
        return $this->hasMany(TeacherSubject::class);
    }

    public function program()
    {
        return $this->belongsTo('App\Program', 'program_id', 'id')->select('id', 'name','name_ar','title','title_ar','description','description_ar', 'code', 'image');
    }
    public function field()
    {
        return $this->belongsTo('App\FieldOfStudy', 'field_id', 'id')->select('id', 'name','image');
    }
    public function country()
    {
        return $this->belongsTo('App\Models\ProgramCountry', 'country_id', 'id')->select('id', 'name','flag');
    }

    public function available_teachers()
    {
        return $this->hasMany(TeacherSubject::class,  'subject_id', 'id');
    }

    public function hired_teachers()
    {
        return $this->hasMany(TeacherSubject::class,  'subject_id', 'id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
