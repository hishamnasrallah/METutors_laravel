<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    public function assignees(){
        return $this->hasMany(UserAssignment::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }
    
}
