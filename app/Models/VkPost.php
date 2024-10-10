<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VkPost extends Model
{
    protected $table = 'vk_posts';
    protected $guarded = [];
    protected $casts = ['data' => 'array'];


    public function wall()
    {
        return $this->belongsTo(VkWall::class);
    }

    public function photos()
    {
        return $this->hasMany(VkPhotos::class, 'post_id');
    }

    public function getPreviewAttribute()
    {
        return array_first($this->photos) ? array_first($this->photos)->first() : [];
    }

    public function getBigPreviewAttribute() {
        return $this->photos->first()[1];

    }

    public function getPhotosAttribute() {
        return $this->photos()->get()->groupBy('vk_id');
    }

    public function getTextAttribute()
    {
        $text = array_get($this->data, 'text', '');
        $text = preg_replace('~\[(.*)\]~ui', '', $text);
        $text = nl2br($text);
        $text = trim($text, ',');
        $text = trim($text, ',');
        return trim($text);
        return $text;
    }

    public function getDateAttribute() {
        return Carbon::createFromTimestamp($this->data['date']);
    }
}
