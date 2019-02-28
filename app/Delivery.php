<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';
    
    protected $fillable = [
        'order_id', 'dispatch_id'
    ];

    public function dispatch()
    {
        return $this->belongsTo('App\Dispatch');
    }

    public function order()
    {
        return $this->hasOne('App\Order');
    }

}
