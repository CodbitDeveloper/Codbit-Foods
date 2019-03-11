<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';

    protected $fillable = [
        'name', 'price', 'category_id', 'description', 'image'
    ];

    protected $table = "items";

    //Relationship: one to many(reverse )
    public function category()
    {
        return $this->belongsTo('App\Category');
    } 

    public function orders()
    {
        return $this->belongsToMany('App\Order', 'item_order')->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany('App\Image');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function ingredients()
    {
        return $this->hasMany('App\Ingredient');
    }

    public function promos()
    {
        return $this->belongsToMany('App\Promo', 'item_promo')->withTimestamps();
    }
}
