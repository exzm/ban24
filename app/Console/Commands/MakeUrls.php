<?php

namespace App\Console\Commands;

use App\Models\Building;
use App\Models\City;
use App\Models\Firm;
use App\Models\Group;
use App\Models\Keyword;
use App\Models\Platform;
use App\Models\Region;
use App\Models\Route;
use App\Models\Station;
use App\Models\Street;
use ElForastero\Transliterate\Transliteration;
use Illuminate\Console\Command;

class MakeUrls extends Command
{
    protected $signature = 'make:url';
    protected $description = 'Generate Urls';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info("Generate City Urls");
        //$this->createCityUrls();
        $this->info("Generate Firm Urls");
        //$this->createFirmUrls();
        //$this->createGroupsUrls();
        $this->createKeywordsUrls();
    }

    private function createKeywordsUrls()
    {
        Keyword::whereNotNull('url')->update(['url' => null]);
        Keyword::chunk(10000, function ($keywords) {
            $this->createUrls($keywords);
            $this->info(' +1000');
        });
    }
    private function createGroupsUrls()
    {
        Group::whereNotNull('url')->update(['url' => null]);
        Group::chunk(1000, function ($groups) {
            $this->createUrls($groups);
            $this->info(' +1000');
        });
    }

    private function createStreetUrls()
    {
        Street::whereNotNull('url')->update(['url' => null]);
        Street::chunk(1000, function ($streets) {
            $this->createUrls($streets);
            $this->info(' +1000');
        });
    }

    private function createUrls($items)
    {
        foreach ($items AS $item) {
            $item->url = $this->getUrl($item);
            $item->update();
        }
    }

    private function getUrl($value, $num = 1)
    {
        if ($num == 1) {
            $value->url = $this->cleanUrl($value->name);
        }
        $class = get_class($value);
        $dubl = $class::where('url', '=', $value->url);
        if (!empty($value->city_id)) {
            $dubl = $dubl->where('city_id', '=', $value->city_id);
        }
        $dubl = $dubl->first();
        if ($dubl) {
            $rand = mt_rand(0, 100000000);
            $value->url = $this->cleanUrl($value->name . '-' . $rand);
            return $this->getUrl($value, $rand);
        } else {
            return $value->url;
        }
    }

    private function cleanUrl($string)
    {
        $url = Transliteration::make($string, ['type' => 'url', 'lowercase' => true]);
        return $url;
    }

    private function createBuildingUrls()
    {
        Building::whereNotNull('url')->update(['url' => null]);
        $streets = Street::all();
        foreach ($streets as $street) {
            Building::whereNull('url')->where('street_id', $street->id)->chunk(1000, function ($buildings) {
                $this->createUrls($buildings);
                $this->info(' +' . $buildings->count());
            });
        }
    }

    private function createCityUrls()
    {
        City::whereNotNull('url')->update(['url' => null]);
        City::chunk(1000, function ($cities) {
            $this->createUrls($cities);
            $this->info(' +1000');
        });
    }

    private function createFirmUrls()
    {
        Firm::whereNotNull('url')->update(['url' => null]);
        Firm::chunk(1000, function ($cities) {
            $this->createUrls($cities);
            $this->info(' +1000');
        });
    }

}
