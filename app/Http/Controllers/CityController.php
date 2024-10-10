<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Group;
use ChrisKonnertz\OpenGraph\OpenGraph;

class CityController extends Controller
{

    private const PER_PAGE = 60;

    public function cityPage($city = 'moskva')
    {
        $city = City::where('url', '=', $city)
            ->with('region')
            ->firstOrFail();

        $groups = Group::where('level', 1)
            ->with('gisGroups')
            ->has('gisGroups')
            ->orderBy('name')
            ->get();

        $firms = $city->firms()
            ->with('contacts', 'city')
            ->limit(self::PER_PAGE)
            ->get();
        $count = $city->firms()->count();

        $this->setTitle("Автопортал Ban24.ru {$city->in()}, новости, цены, отзывы и много другое для " . number_format($count, 0, ' ', ' ') . " компан" . ending($count, ['ий', 'ии', 'ий']) . ".");
        $this->setDescription("Всё для автолюбителей {$city->in()}. {$groups->take(5)->implode('name', ', ')} - отзывы, цены, новости компаний и много другое на сайте Ban24.ru.");

        $og = new OpenGraph();
        $og->title($this->title)
            ->type('article')
            ->image($city->getImg('650,450', $zoom = 10))
            ->locale('ru_RU')
            ->description($this->description)
            ->url($this->canonical);

        $this->addBread(route('city', [$city->url]), $city->name, $city->name);

        $data = [
            'city'     => $city,
            'og'       => $og,
            'groups'   => $groups,
            'firms'    => $firms,
            'count'    => $count,
            'comments' => $city->comments()->limit(5)->latest()->get(),
            'footer'   => "Новости, цены, отзывы и много другое для " . number_format($count, 0, ' ', ' ') . " компан" . ending($count, ['ий', 'ии', 'ий'])
        ];
        return view('city.city', $data);
    }

    public function select()
    {
        $data = [];
        return view('popup.city-select', $data);
    }


    public function search($str)
    {
        $cities = City::where('name', 'like', "{$str}%")
            ->select(['name', 'url'])
            ->groupBy('name')
            ->limit(10)
            ->get();

        return response()->json($cities);
    }
}
