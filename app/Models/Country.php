<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Country extends Model
{
    protected $table = 'country';
    protected $guarded = [];

    public function regions()
    {
        return $this->hasMany('App\Models\Region');
    }
}
