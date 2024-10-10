<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Firm;
use App\Models\GisGroup;
use App\Models\GroupFirm;
use App\Models\Keyword;
use App\Models\KeywordFirm;
use App\Models\Region;
use App\Models\Station;
use Illuminate\Console\Command;
use samdark\sitemap\Index;
use samdark\sitemap\Sitemap;

class MakeSitemap extends Command
{

    const SITEMAP_DIR = 'JSDNFkJjKSfdKJKFSHUehewuihKLFHDSKJFioheNKJDBSB';
    const SITEMAP_INDEX_NAME = 'UdnajsUhdsjaWEEEEIjldksajdlkj.xml';
    const MAX_URLS = 10000;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.xml';


    protected $sitemap;

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        \File::deleteDirectory($this->getSitemapDir());
        \File::delete(public_path() . '/' . self::SITEMAP_INDEX_NAME);
        \File::makeDirectory($this->getSitemapDir());

        $this->setGenerator();
        $this->info('Groups');
        $this->createGroups();
        $this->info('Cities');
        $this->createCities();
        $this->info('Firms');
        $this->createFirms();
        $this->info('Keys');
        $this->createKeys();

        $this->createIndex();
    }


    private function getSitemapDir()
    {
        return public_path() . '/' . self::SITEMAP_DIR;
    }


    private function setGenerator()
    {
        $this->sitemap = new Sitemap($this->getSitemapPath('sitemap'));
        $this->sitemap->setMaxUrls(self::MAX_URLS);
        return $this->sitemap;
    }

    private function getSitemapPath($name)
    {
        return $this->getSitemapDir() . '/' . $name . '.xml';
    }

    private function createCities()
    {
        $cities = City::with('region')->get();
        foreach ($cities AS $city) {
            $url = route('city', [$city->url]);
            $url = mb_strtolower($url, 'utf-8');
            $this->sitemap->addItem($url, time(), Sitemap::DAILY, 0.9);
        }
    }

    private function createKeys()
    {
        $cities = City::all();
        foreach ($cities as $city) {
            $keys = KeywordFirm::where('city_id', $city->id)
                ->groupBy(['keyword_id'])->get();
            foreach ($keys as $key) {
                $key = Keyword::where('id', $key->keyword_id)->first();
                $url = route('keyword', [$city->url, $key->url]);
                $url = mb_strtolower($url, 'utf-8');
                $this->sitemap->addItem($url, time(), Sitemap::DAILY, 0.8);
            }
        }

    }

    private function createGroups()
    {
        $gisGroups = GisGroup::whereNotNull('group_id')
            ->with('group')
            ->get();
        foreach ($gisGroups as $gisGroup) {
            $firms = GroupFirm::where('gis_group_id', $gisGroup->id)
                ->groupBy('city_id')
                ->get();
            $group = $gisGroup->group;
            if ($group) {
                foreach ($firms as $firm) {
                    $city = City::where('id', $firm->city_id)->first();
                    if (!$city)
                        continue;
                    $url = route('group', [$city->url, $group->url]);
                    $url = mb_strtolower($url, 'utf-8');
                    $this->sitemap->addItem($url, time(), Sitemap::DAILY, 0.8);
                }
            }
        }
    }

    private function createFirms()
    {
        $firms = Firm::with('city')->get();
        foreach ($firms as $firm) {
            $city = $firm->city;
            if (!$city)
                continue;
            $url = route('firm', [$city->url, $firm->url]);
            $url = mb_strtolower($url, 'utf-8');
            $this->sitemap->addItem($url, time(), Sitemap::DAILY, 0.7);
        }
    }


    private function createIndex()
    {
        $index = new Index(public_path() . '/' . self::SITEMAP_INDEX_NAME);
        $files = $this->sitemap->getSitemapUrls(asset(self::SITEMAP_DIR) . '/');
        foreach ($files AS $file) {
            $index->addSitemap($file, time());
        }
        $index->write();
        $this->sitemap->write();
    }
}
