<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

     protected $casts = [
        'urls' => 'array',
        'files' => 'array',
    ];

    public function assignees(){
        return $this->hasMany(UserAssignment::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }
    
}
