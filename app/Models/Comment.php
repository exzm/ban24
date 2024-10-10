<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $guarded = [];


    public function firm()
    {
        return $this->belongsTo('App\Models\Firm')->with(['contacts', 'city']);
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
}
