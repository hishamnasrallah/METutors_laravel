<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherInterviewRequest extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User')->select('first_name','last_name','role_name','mobile','email','bio','verified','avatar','cover_img','address','status','created_at')->where('id',$this->user_id);
    }
}
