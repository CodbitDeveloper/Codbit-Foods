<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';

    protected $fillable = [
        'customer_id', 'paymentType_id', 'status', 'total_price', 'address', 'extra_note'
    ];

    protected $table = "orders";

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }


    public function items()
    {
        return $this->belongsToMany('App\Item', 'item_order')->withTimestamps();
    }

    public function paymentTypes()
    {
        return $this->hasMany('App\paymentType');
    }

    public function delivery()
    {
        return $this->belongsTo('App\Delivery');
    }

    public function checkouts()
    {
        return $this->hasMany('App\Checkout');
    }

    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
}
