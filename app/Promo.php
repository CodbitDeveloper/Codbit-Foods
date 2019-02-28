<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';

    protected $fillable = [
        'name', 'promo_amount',
    ];

    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
