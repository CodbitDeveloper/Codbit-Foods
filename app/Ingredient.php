<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';

    protected $fillable = [
        'item_id', 'name',
    ];

    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}
