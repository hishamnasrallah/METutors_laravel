<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastActivity extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'role_name', 'course_id', 'field_of_study_id', 'program_id', 'country_id', 'updated_at'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
