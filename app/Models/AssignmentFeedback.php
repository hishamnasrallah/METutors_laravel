<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentFeedback extends Model
{
    use HasFactory;

       protected $casts = [
       
        'file' => 'array',
    ];
}
