<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkout extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';

    protected $fillable = [
        'checkoutUrl', 'checkoutId', 'clientReference', 'order_id'
    ];

    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
