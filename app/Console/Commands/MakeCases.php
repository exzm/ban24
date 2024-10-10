<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\District;
use App\Models\Region;
use App\Models\Route;
use App\Models\Station;
use App\Models\Street;
use Illuminate\Console\Command;
use MCurl\Client;

class MakeCases extends Command
{
    protected $client;
    protected $signature = 'make:cases';
    protected $description = 'Parse morpher.ru';
    private $token = '8f0a239c-435c-48af-a3c5-a4f4e7dc4e67';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->client = new Client();
        $this->regions();
        $this->cities();
        $this->districts();
        $this->streets();
        $this->stations();
        $this->routes();
    }

    private function stations()
    {
        $stations = Station::all();
        foreach ($stations as $station) {
            $station->cases = $this->getCases($station->name);
            $station->save();
            $this->info($station->name);
        }
    }

    private function streets()
    {
        $streets = Street::all();
        foreach ($streets as $street) {
            $street->cases = $this->getCases($street->name);
            $street->save();
            $this->info($street->name);
        }
    }

    private function districts()
    {
        $districts = District::all();
        foreach ($districts as $district) {
            $district->cases = $this->getCases($district->name);
            $district->save();
            $this->info($district->name);
        }
    }

    private function cities()
    {
        $cities = City::all();
        foreach ($cities as $n => $city) {
            $city->cases = $this->getCases($city->name);
            $city->save();
            $this->info($city->name);
        }
    }

    private function regions()
    {
        $regions = Region::all();
        foreach ($regions as $region) {
            $region->cases = $this->getCases($region->name);
            $region->save();
            $this->info($region->name);
        }
    }

    private function getCases($name)
    {
        $cases = [
            'gde'    => 'где',
            'kuda'   => 'куда',
            'otkuda' => 'откуда',
            'imin'   => 'И',
            'rod'    => 'Р',
            'dat'    => 'Д',
            'vin'    => 'В',
            'tvor'   => 'Т',
            'pred'   => 'П',
        ];
        $result = [
            'imin' => $name,
            'rod'  => $name,
            'dat'  => $name,
            'vin'  => $name,
            'tvor' => $name,
            'pred' => $name,
        ];
        $url = urlencode($name);
        $url = "https://ws3.morpher.ru/russian/declension?s={$url}&token={$this->token}";
        $content = $this->client->get($url);

        if ($content->getHttpCode() != 200) {
            $this->error($content->getHttpCode());
            return null;
        }
        $content = $content->getBody();
        $content = new \SimpleXMLElement($content);
        foreach ($content as $key => $item) {
            if (in_array($key, $cases)) {
                $case = array_search($key, $cases);
                $result[$case] = (string)$item;
            }
        }

        return $result;
    }
}
