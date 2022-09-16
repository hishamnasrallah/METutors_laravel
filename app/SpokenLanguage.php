<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpokenLanguage extends Model
{
     public function language()
    {
        return $this->belongsTo('App\Language', 'language', 'id');
    }
}
