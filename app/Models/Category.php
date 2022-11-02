<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    static $cacheKey = 'categories';

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id', 'id');
    }

    public function subCategories()
    {
        return $this->hasMany($this, 'parent_id', 'id')->orderBy('order', 'asc');
    }

}
