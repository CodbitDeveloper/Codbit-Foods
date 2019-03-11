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

    public function items()
    {
        return $this->belongsToMany('App\Item', 'item_promo')->withTimestamps();
    }

    public function customers()
    {
        return $this->belongsToMany('App\Customer', 'customer_promo')->withTimestamps();
    }
}
