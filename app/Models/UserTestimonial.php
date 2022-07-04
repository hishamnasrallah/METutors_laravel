<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTestimonial extends Model
{
    use HasFactory;

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id')->select('id','id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function testimonial()
    {
        return $this->belongsTo(Testimonial::class, 'testimonial_id', 'id')->select('id', 'name');
    }
}
