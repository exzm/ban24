<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $table = 'stations';
    protected $guarded = [];
    protected $casts = ['platforms' => 'array'];

    public function city(){
        return $this->belongsTo('App\City');
    }
}
