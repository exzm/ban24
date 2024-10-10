<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $guarded = [];

    public function firm()
    {
        return $this->belongsTo('App\Firm');
    }

    public function getIconAttribute()
    {
        $icons = [
            'facebook'      => '<i class="fab fa-facebook-square"></i>',
            'googleplus'    => '<i class="fab fa-google-plus-square"></i>',
            'instagram'     => '<i class="fab fa-instagram"></i>',
            'linkedin'      => '<i class="fab fa-linkedin"></i>',
            'pinterest'     => '<i class="fab fa-pinterest-square"></i>',
            'twitter'       => '<i class="fab fa-twitter-square"></i>',
            'vkontakte'     => '<i class="fab fa-vk"></i>',
            'youtube'       => '<i class="fab fa-youtube-square"></i>',
            'odnoklassniki' => '<i class="fab fa-odnoklassniki-square"></i>',
        ];

        return array_get($icons, $this->type, '');
    }

}
