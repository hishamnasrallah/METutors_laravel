<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundClass extends Model
{
    use HasFactory;

    public function academic_class()
    {
        return $this->belongsTo(AcademicClass::class);
    }
}
