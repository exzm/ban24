<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Firm extends Model
{

    protected $guarded = [];
    protected $table = 'firms';
    protected $casts = ['links' => 'array', 'worktime' => 'array'];

    public function gisGroups()
    {
        return $this->belongsToMany('App\Models\GisGroup', 'gis_group_firm');
    }

    public function keywords()
    {
        return $this->belongsToMany('App\Models\Keyword', 'firm_keyword');
    }

    public function attributes()
    {
        return $this->belongsToMany('App\Models\Attribute', 'attribute_firm');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function contacts()
    {
        return $this->hasMany('App\Models\Contact');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function street()
    {
        return $this->belongsTo('App\Models\Street');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }

    public function buildingInfo()
    {
        return $this->belongsTo('App\Models\Building', 'building_id', 'id');
    }


    public function photos()
    {
        return $this->hasMany('App\Models\Photo');
    }


    public function getGroupsAttribute()
    {
        $groups = collect();
        foreach ($this->gisGroups as $gisGroup) {
            if ($gisGroup->group) {
                $groups->push($gisGroup->group);
            } else {
                //$groups->push($gisGroup);
            }
        }
        return $groups->unique('name');
    }


    public function getSitesAttribute()
    {
        $result = [];
        if ($this->contacts) {
            foreach ($this->contacts->where('type', 'website') as $contact) {
                $result[] = $contact;
            }
        }
        return collect($result);
    }

    public function getSocialAttribute()
    {
        $result = [];
        if ($this->contacts) {
            $types = [
                'facebook',
                'googleplus',
                'instagram',
                'linkedin',
                'pinterest',
                'twitter',
                'vkontakte',
                'youtube',
                'odnoklassniki'
            ];
            foreach ($this->contacts->whereIn('type', $types) as $contact) {
                $result[] = $contact;
            }
        }
        return collect($result);
    }

    public function getPhonesAttribute()
    {
        $result = [];
        if ($this->contacts) {
            foreach ($this->contacts->where('type', 'phone') as $contact) {
                $result[] = $contact;
            }
        }
        return collect($result);
    }

    public function getFaxesAttribute()
    {
        $result = [];
        if ($this->contacts) {
            foreach ($this->contacts->where('type', 'fax') as $contact) {
                $result[] = $contact;
            }
        }
        return collect($result);
    }

    public function getEmailsAttribute()
    {
        $result = [];
        if ($this->contacts) {
            foreach ($this->contacts->where('type', 'email') as $contact) {
                $result[] = $contact;
            }
        }
        return collect($result);
    }

    public function getVkPostsAttribute()
    {
        $posts = null;
        $wall = $this->social->where('type', 'vkontakte')->first();
        if ($wall) {
            $posts = VkWall::with('posts', 'posts.photos')
                ->where('url', $wall->url)
                ->first();
            if ($posts) {
                $posts = $posts->posts()->latest();
            }
        }
        return $posts ?: null;
    }

    public function isOpen($timezone = null)
    {
        $carbon = new Carbon('now', $timezone);
        $day = $carbon->format('D');
        $worktime = array_get($this->worktime, "{$day}.working_hours");
        if ($worktime) {
            if (array_get($this->worktime, 'is_24x7')) {
                return true;
            }
            foreach ($worktime as $time) {
                try {
                    $from = $carbon->parse("{$day} {$time['from']}", $timezone);
                    $to = $carbon->parse("{$day} {$time['to']}", $timezone);
                    if ($carbon->between($from, $to)) {
                        return true;
                    }
                } catch (\Exception $exception) {
                    return false;
                }
            }
        }
        return false;
    }

    public function getStationsAttribute()
    {
        return array_get($this->links, 'nearest_stations');
    }

    public function getLatAttribute()
    {
        return $this->getOriginal('lat') != 0 ? $this->getOriginal('lat') * 1.0000002 : $this->city->lat;
    }

    public function getLonAttribute()
    {
        return $this->getOriginal('lon') != 0 ? $this->getOriginal('lon') * 1.0000002 : $this->city->lon;
    }

    public function getImg($size = '650,450', $zoom = 16)
    {
        return "https://static-maps.yandex.ru/1.x/?ll={$this->lon},{$this->lat}&size={$size}&z={$zoom}&l=map&lang=ru_RU&pt={$this->lon},{$this->lat},vkbkm";
    }

    public function getRatingAttribute()
    {
        $rating = collect();
        $rating->put('count', $this->rating_count ?: 0);
        $rating->put('avg', $this->rating_count ? $this->rating_value / $this->rating_count : 0);
        return $rating;
    }

    public function getPaymentsAttribute()
    {
        return $this->relations['attributes']->filter(function ($element) {
            return mb_strpos($element->tag, 'general_payment_type') !== false;
        });
    }

    public function getAttributesAttribute()
    {
        return $this->relations['attributes']->filter(function ($element) {
            return mb_strpos($element->tag, 'general_payment_type') === false;
        });
    }

    public function near($limit)
    {
        $sql = "SELECT firms.*, @dlat := lat - {$this->lat}, @dlon := lon - {$this->lon}, SQRT(@dlat*@dlat+@dlon*@dlon) * 61000 AS distance FROM firms
                RIGHT JOIN (
                    SELECT DISTINCT firm_id FROM gis_group_firm
                    RIGHT JOIN (
                        SELECT gis_group_id FROM gis_group_firm
                        WHERE firm_id = {$this->id}
                    ) AS possible_group ON gis_group_firm.gis_group_id = possible_group.gis_group_id
                    WHERE city_id = {$this->city->id} AND firm_id <> {$this->id}
                ) AS firm_group_list ON firm_group_list.firm_id = firms.id
                WHERE lon IS NOT NULL
                ORDER BY distance
                LIMIT {$limit}";

        return DB::select($sql);
    }

    public function getSubtitleCasesAttribute()
    {
        $result = [];
        if ($this->subtitle) {
            $result = Subtitle::where('name', $this->subtitle)->first();
        }
        return $result;
    }

    public function getStrWorktimeAttribute()
    {
        $result = '';
        if (array_get($this->worktime, 'is_24x7')) {
            return 'круглосуточно';
        }
        $mon = array_get($this->worktime, "Mon.working_hours");
        if ($mon) {
            $result = 'пн-пт ';
            foreach ($mon as $n => $time) {
                $result .= ($n > 0 ? ", " : "") . "с {$time['from']} до {$time['to']}";
            }
            $sat = array_get($this->worktime, "Sat.working_hours", []);
            if (!$sat) {
                $result .= ', сб выходной';
            }
            foreach ($sat as $n => $time) {
                $result .= ', сб ';
                $result .= ($n > 0 ? ", " : "") . "с {$time['from']} до {$time['to']}";
            }
            $sun = array_get($this->worktime, "Sun.working_hours", []);
            if (!$sun) {
                $result .= ', вс выходной';
            }
            foreach ($sun as $n => $time) {
                $result .= ', вс ';
                $result .= ($n > 0 ? ", " : "") . "с {$time['from']} до {$time['to']}";
            }
        }
        return $result;
    }

}
