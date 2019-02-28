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
        'user_id', 'item_id', 'comment', 'ratings',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}
