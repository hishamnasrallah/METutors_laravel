<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceDocument extends Model
{
    use HasFactory;
    protected $casts = [
        'file' => 'array',
    ];


    public function user(){
        return $this->belongsTo(User::class)->select('id', 'id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }
    
}
