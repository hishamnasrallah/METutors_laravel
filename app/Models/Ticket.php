<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{


    protected $fillable = [
        'user_id', 'category_id', 'ticket_id', 'subject', 'priority', 'message', 'status', 'file'
    ];
    public function priority()
    {
        return $this->belongsTo(TicketPriorities::class, 'priority', 'id');
    }
    public function category()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class)->latest();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function ticket_comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select(['id', 'id_number', 'first_name', 'last_name', 'last_name', 'email', 'role_name', 'verified', 'avatar']);
    }
}
