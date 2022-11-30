<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id','id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'bio', 'country', 'avatar');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function field()
    {
        return $this->belongsTo(FieldOfStudy::class, 'field_id', 'id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function teaching_specification()
    {
        return $this->belongsTo(TeachingSpecification::class, 'user_id', 'user_id');
    }

    public function teacher_qualification()
    {
        return $this->belongsTo(TeachingQualification::class, 'user_id', 'user_id');
    }

    public function teacher_subjects()
    {
        return $this->hasMany(TeacherSubject::class, 'user_id', 'user_id');
    }
}
