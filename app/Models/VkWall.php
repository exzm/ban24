<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VkWall extends Model
{
    protected $table = 'vk_walls';
    protected $guarded = [];
    protected $casts = ['data' => 'array'];


    public function posts()
    {
        return $this->hasMany(VkPost::class, 'wall_id');
    }

    public function getPreviewAttribute()
    {
        $img = "/storage/img-walls/{$this->id}/photo_50.jpg";
        $path = public_path() . $img;
        if (file_exists($path)) {
            return $img;
        };
        return false;

    }

    public function getDescriptionAttribute()
    {
        return array_get($this->data, 'description', '');

    }
}
