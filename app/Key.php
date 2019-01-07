<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    protected $table = 'whitelist';

    public function lock()
    {
        return $this->belongsTo('App\Lock');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}