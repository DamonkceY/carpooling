<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'departplace','arrivalplace','departuredatetime','contact','avplaces'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function appliedtrips(){
        return $this->hasMany('App\Tripusers');
    }
}
