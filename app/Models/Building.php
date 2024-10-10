<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $guarded = [];
    protected $table = 'buildings';
    public $timestamps = false;
    protected $casts = ['entrances' => 'array'];

    public function street()
    {
        return $this->belongsTo('App\Street');
    }

    public function district()
    {
        return $this->belongsTo('App\District');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

}
