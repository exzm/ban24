<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtitle extends Model
{
    protected $table = 'subtitles';
    protected $guarded = [];
    protected $casts = ['data' => 'array'];
    public $timestamps = false;

    public function caseEd($case = RU_IM)
    {
        return array_get($this->data, "ed.{$case}", $this->name);
    }

    public function caseMn($case = RU_IM)
    {
        return array_get($this->data, "mn.{$case}", $this->name);
    }

}
