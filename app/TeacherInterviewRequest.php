<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherInterviewRequest extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function userCertificates()
    {
        return $this->hasMany('App\TeacherDocument', 'user_id', 'user_id')->where('document','certificates');
    }
    public function userDegrees()
    {
        return $this->hasMany('App\TeacherDocument', 'user_id', 'user_id')->where('document','degrees');
    }
    public function userResume()
    {
        return $this->hasMany('App\TeacherDocument', 'user_id', 'user_id')->where('document','resume');
    }
    public function userDocuments()
    {
        return $this->hasMany('App\TeacherDocument', 'user_id', 'user_id');
    }
    public function userMetas()
    {
        return $this->hasMany('App\Models\UserMeta', 'user_id', 'user_id');
    }
    
    public function teacherSpecifications()
    {
        return $this->hasOne('App\TeachingSpecification', 'user_id', 'user_id');
    }

    public function teacherQualifications()
    {
        return $this->hasOne('App\TeachingQualification', 'user_id', 'user_id');
    }

    public function spokenLanguages()
    {
        return $this->hasMany(SpokenLanguage::class);
    }
}
