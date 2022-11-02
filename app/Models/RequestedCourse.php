<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestedCourse extends Model
{
    use HasFactory;

       public function program()
    {
        return $this->belongsTo('App\Program', 'program_id', 'id')->select('id', 'name','name_ar', 'code');
    }
      public function country()
    {
        return $this->belongsTo('App\Models\ProgramCountry', 'country_id', 'id')->select('id', 'name');
    }  
      public function language()
    {
        return $this->belongsTo('App\Language', 'language_preference', 'id')->select('id', 'name');
    }
}
