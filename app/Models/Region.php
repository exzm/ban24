<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Region
 *
 * @property-read \App\Country $country
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\City[] $cities
 * @mixin \Eloquent
 */
class Region extends Model
{
    protected $table = 'region';
    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function cities()
    {
        return $this->hasMany('App\Models\City');
    }
}
