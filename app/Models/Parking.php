<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'full_name',
        'address_name',
        'is_paid',
        'type',
        'level_count',
        'street_id',
        'city_id',
        'district_id',
        'access_type',
        'comment',
        'schedule',
        'capacity',
        'lon',
        'lat',
    ];
}
