<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';

    protected $fillable = [
        'filename', 'item_id'
    ];

    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}
