<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisputeTicket extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class)->select('id', 'id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }

    public function dispute_comments()
    {
        return $this->hasMany(DisputeComment::class,'dispute_id','dispute_id');
    }
}
