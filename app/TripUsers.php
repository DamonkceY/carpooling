<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripUsers extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function trip(){
        return $this->belongsTo('App\Trip');
    }
}
