<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherDocument extends Model
{
     protected $table = 'teacher_documents';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
