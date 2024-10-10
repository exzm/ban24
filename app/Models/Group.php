<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    protected $guarded = [];
    public $timestamps = false;
    protected $casts = ['data' => 'array'];


    public function gisGroups()
    {
        return $this->hasMany('App\Models\GisGroup');
    }

    public function keywords()
    {
        return $this->hasMany('App\Models\Keyword');
    }

    public function childrens()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function comments(City $city, $limit = 5)
    {
        $gis_groups = $this->gisGroups()->get();
        $comments = Comment::select('comments.*')
            ->with('firm')
            ->where('comments.city_id', $city->id)
            ->join('gis_group_firm', 'comments.firm_id', 'gis_group_firm.firm_id')
            ->whereIn('gis_group_firm.gis_group_id', $gis_groups->pluck('id'))
            ->groupBy('comments.id')
            ->limit($limit);
        return $comments;
    }


    public function getIconAttribute()
    {
        $types = [
            115502 => '<i class="fas fa-tire-flat"></i>',
            112302 => '<i class="fas fa-car-tilt"></i>',
            111390 => '<i class="fas fa-oil-can"></i>',
            107358 => '<i class="fas fa-car-mechanic"></i>',
            107290 => '<i class="fas fa-car-mechanic"></i>',
            16919  => '<i class="far fa-car-battery"></i>',
            2987   => '<i class="fas fa-paint-brush-alt"></i>',
            330    => '<i class="far fa-car-crash"></i>',
            329    => '<i class="fas fa-paint-brush-alt"></i>',
            115    => '<i class="fas fa-car-mechanic"></i>',
            114    => '<i class="fas fa-car-wash"></i>',
            111    => '<i class="fas fa-oil-can"></i>',
            100    => '<i class="fas fa-car-mechanic"></i>',
            55     => '<i class="fas fa-volume"></i>',
            50     => '<i class="fas fa-car-mechanic"></i>',
            48     => '<i class="fas fa-engine-warning"></i>',
            46     => '<i class="far fa-car-alt"></i>',
            45     => '<i class="fas fa-car-mechanic"></i>',
            43     => '<i class="fas fa-skeleton"></i>',
            41     => '<i class="fas fa-car-mechanic"></i>',
            40     => '<i class="fas fa-car-mechanic"></i>',
            35     => '<i class="fas fa-car-mechanic"></i>',
            31     => '<i class="far fa-truck-monster"></i>',
            8      => '<i class="far fa-car-crash"></i>',
        ];
        return array_get($types, $this->id, '<i class="far fa-car-alt"></i>');
    }

    public function caseEd($case = RU_IM)
    {
        return array_get($this->data, "ed.{$case}", $this->name);
    }

    public function caseMn($case = RU_IM)
    {
        return array_get($this->data, "mn.{$case}", $this->name);
    }
}
