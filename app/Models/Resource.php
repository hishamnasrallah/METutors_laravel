<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    public function class(){
        return $this->belongsTo(AcademicClass::class,'id','resource_id');
    }
}
