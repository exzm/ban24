<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\City
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Building[] $buildings
 * @property-read \App\Models\Region $region
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Station[] $stations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Street[] $streets
 * @mixin \Eloquent
 */
class City extends Model
{
    protected $table = 'cities';
    protected $guarded = [];
    protected $casts = ['geometry' => 'array', 'cases' => 'array'];
    public $timestamps = false;

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }


    public function firms()
    {
        return $this->hasMany('App\Models\Firm');
    }


    public function buildings()
    {
        return $this->hasMany('App\Models\Building');
    }

    public function streets()
    {
        return $this->hasMany('App\Models\Street');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }


    public function caseEd($case = RU_IM)
    {
        return array_get($this->cases, "ed.{$case}", $this->name);
    }

    public function caseMn($case = RU_IM)
    {
        return array_get($this->cases, "mn.{$case}", $this->name);
    }

    public function in()
    {
        return $this->caseEd(RU_GDE);
    }

    public function getLatAttribute()
    {
        return $this->getOriginal('lat') * 1.0000001;
    }

    public function getLonAttribute()
    {
        return $this->getOriginal('lon') * 1.0000001;
    }

    public function getImg($size = '650,450', $zoom = 16)
    {
        return "https://static-maps.yandex.ru/1.x/?ll={$this->lon},{$this->lat}&size={$size}&z={$zoom}&l=map&lang=ru_RU&pt={$this->lon},{$this->lat},vkbkm";
    }

    public function getTimeAttribute()
    {
        return new Carbon('now', $this->timezone);
    }
}
