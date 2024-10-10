<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Attribute
 *
 * @mixin \Eloquent
 */
class AttributeFirm extends Model
{
    protected $table = 'attribute_firm';
    public $timestamps = false;
    protected $guarded = [];
}
