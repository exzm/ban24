<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GisGroup extends Model
{

    protected $table = 'gis_groups';
    protected $guarded = [];

    public function parent()
    {
        if (!empty($this->parent_id))
            return GisGroup::where('id', $this->parent_id)->first();
        return null;
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }
}
