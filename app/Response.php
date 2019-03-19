<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Response extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';

    protected $fillable = ['user_id', 'feedback_id', 'response'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
