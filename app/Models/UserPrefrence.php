<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPrefrence extends Model
{
    use HasFactory;

    public function preferred_language()
    {
        return $this->belongsTo('App\Language', 'preferred_language', 'id');
    }
    public function teacher_language()
    {
        return $this->belongsTo('App\Language', 'teacher_language', 'id');
    }

    public function spoken_language()
    {
        return $this->belongsTo('App\Language', 'teacher_language', 'id');
    }
}
