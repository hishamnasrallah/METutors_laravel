<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
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
}
