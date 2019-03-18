<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';

    protected $table = 'comments';

    protected $fillable = [
        'customer_id', 'item_id', 'comment', 'ratings', 'branch_id', 
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
}
