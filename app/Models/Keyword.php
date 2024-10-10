<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Keyword
 *
 * @mixin \Eloquent
 */
class Keyword extends Model
{
    protected $guarded = [];
    protected $table = 'keywords';
    protected $casts = ['data' => 'array'];
    public $timestamps = false;


    public function comments(City $city, $limit = 5)
    {
        $comments = Comment::select('comments.*')
            ->with('firm')
            ->where('comments.city_id', $city->id)
            ->join('firm_keyword', 'comments.firm_id', 'firm_keyword.firm_id')
            ->where('firm_keyword.keyword_id', $this->id)
            ->groupBy('comments.id')
            ->limit($limit);
        return $comments;
    }


    public function getOptionsAttribute()
    {
        return array_get($this->data, 'options');
    }

    public function getNameAttribute()
    {
        return trim($this->getOriginal('name'));
    }
}
