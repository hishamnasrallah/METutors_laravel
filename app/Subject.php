<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{

     protected $fillable = ['program_id','country_id','grade','field_id','name','price_per_hour'];

    public function teacherSubject() {
        return $this->hasMany(TeacherSubject::class);
    }

       public function program()
    {
        return $this->belongsTo('App\Program', 'program_id', 'id')->select('id','name');
    }  
     public function country()
    {
        return $this->belongsTo('App\Models\ProgramCountry', 'country_id', 'id')->select('id','name');
    }
}
