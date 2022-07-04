<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{


     protected $fillable = [
      'ticket_id', 'user_id', 'comment', 'file'
    ];
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
     public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select(['id','id_number','first_name','last_name','last_name','email','verified','avatar']);
    }
}
