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

    public function assignees()
    {
        return $this->hasMany(UserAssignment::class);
        // return $this->hasMany(UserAssignment::class)->latest('updated_at')->groupBy('user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->select('id', 'id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }
}
