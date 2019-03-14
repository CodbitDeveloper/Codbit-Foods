<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class paymentType extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';

    protected $fillable = [
        'name',
    ];

    /*public function paymentType_params()
    {
        return $this->hasMany(paymentType_Param::class);
    }*/

    public function order()
    {
        return $this->belongsToMany('App\Order');
    }
}
