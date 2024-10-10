<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\GroupFirm
 *
 * @mixin \Eloquent
 */
class GroupFirm extends Model
{
    protected $guarded = [];
    protected $table = 'gis_group_firm';


    public function firm()
    {
        return $this->belongsTo('App\Models\Firm');
    }

    public function gisGroup() {
        return $this->belongsTo('App\Models\GisGroup');
    }
}
