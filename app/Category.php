<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';

    protected $fillable = [
        'name', 'image'
    ];

    //Relationship: one to many
    public function items()
    {
        return $this->hasMany('App\Item');
    }

}
