<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisputeComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispute_id', 'user_id', 'comment', 'file'
    ];

    public function user(){
        return $this->belongsTo(User::class)->select('id', 'id_number', 'first_name', 'last_name', 'role_name', 'email', 'mobile', 'avatar');
    }
}
