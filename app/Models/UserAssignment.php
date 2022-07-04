<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAssignment extends Model
{
    use HasFactory;

    protected $casts = [

        'file' => 'array',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id', 'id');
    }

    public function assignee()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'id', 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id','id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function feedback()
    {
        return $this->hasMany(AssignmentFeedback::class, 'assignment_id', 'assignment_id')->select('id', 'assignment_id', 'student_id', 'review', 'rating', 'file', 'feedback_by');
    }
}
