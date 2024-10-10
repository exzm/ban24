<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\District
 *
 * @mixin \Eloquent
 */
class District extends Model
{
    protected $fillable = ['name'];
    protected $table = 'districts';

}
