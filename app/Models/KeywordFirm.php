<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Keyword
 *
 * @mixin \Eloquent
 */
class KeywordFirm extends Model
{
    protected $guarded = [];
    protected $table = 'firm_keyword';
    public $timestamps = false;


    public function firm()
    {
        return $this->belongsTo('App\Models\Firm');
    }

}
