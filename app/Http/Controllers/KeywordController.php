<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupFilter;
use App\Models\City;
use App\Models\Firm;
use App\Models\Group;
use App\Models\GroupFirm;
use App\Models\Keyword;
use App\Models\KeywordFirm;
use ChrisKonnertz\OpenGraph\OpenGraph;

class KeywordController extends Controller
{
    private const PER_PAGE = 60;

    public function keywordPage($city, $key)
    {
        $city = City::where('url', $city)->firstOrFail();
        $key = Keyword::where('url', $key)->firstOrFail();

        $firms = KeywordFirm::with(['firm', 'firm.city', 'firm.contacts'])
            ->where('city_id', $city->id)
            ->where('keyword_id', $key->id)
            ->paginate(self::PER_PAGE);

        $count = $firms->total();

        if ($count == 0) {
            abort(404);
        }

        $pager = $firms->links();
        $firms = $firms->pluck('firm');

        $img = $this->getImg($firms, $city, '650,450');


        $this->setTitle("{$key->name} {$city->in()}, {$count} компан" . ending($count, ['ия', 'ии', 'ий']) . " на карте с контактами и отзывами.");
        $this->setDescription("Найдено {$count} компан" . ending($count, ['ия', 'ии', 'ий']) . " {$city->in()}. Вы легко сможете найти работающие рядом с вами компании из категории «{$key->name}» на карте.");
        $this->setKeywords(array_get($key->options, 'list', []));
        $this->addJs('//api-maps.yandex.ru/2.1.65/?lang=ru-RU');

        $og = new OpenGraph();
        $og->title($this->title)
            ->type('article')
            ->image($img)
            ->locale('ru_RU')
            ->description($this->description)
            ->url($this->canonical);


        $this->addBread(route('city', [$city->url]), $city->name, $city->name);
        $this->addBread(route('group', [$city->url, $key->url]), $key->name, $key->name);

        $data = [
            'city'     => $city,
            'og'       => $og,
            'count'    => $count,
            'key'      => $key,
            'img'      => $img,
            'comments' => $key->comments($city, 6)->with(['firm'])->get(),
            'firms'    => $firms,
            'pager'    => $pager,
            'footer'   => "{$key->name} {$city->in()}"
        ];
        return view('keyword.keyword', $data);
    }

    public function filter($city, $key, GroupFilter $request)
    {
        $city = City::where('url', $city)->firstOrFail();
        $key = Keyword::where('url', $key)->firstOrFail();

        $firms = KeywordFirm::where('city_id', $city->id)
            ->where('city_id', $city->id)
            ->where('keyword_id', $key->id)
            ->get();
        $firms = Firm::with(['city', 'contacts'])->whereIn('id', $firms->pluck('firm_id'));


        if ($request->get('open')) {
            $ids = [];
            foreach ($firms->get() as $firm) {
                if ($firm->isOpen($city->timezone)) {
                    $ids[] = $firm->id;
                }
            }
            $firms = Firm::with(['city', 'contacts'])->whereIn('id', $ids);
        }

        if ($request->get('near')) {
            $lat = $request->get('lat', 0.0);
            $lon = $request->get('lon', 0.0);
            $firms = $firms->whereRaw("SQRT(69.1*69.1*(lat - {$lat})*(lat - $lat) + 53*53*(lon - {$lon})*(lon - {$lon}))*1609 < 3000")
                ->selectRaw("*, SQRT(69.1*69.1*(lat - {$lat})*(lat - $lat) + 53*53*(lon - {$lon})*(lon - {$lon}))*1609 AS distance");
        }

        if ($request->route()->getName() == 'keyword-markers') {
            return $this->markers($firms->get(), $city);
        }

        $firms = $firms->paginate(self::PER_PAGE);
        $pager = $firms->links();

        $data = [
            'city'  => $city,
            'firms' => $firms,
            'pager' => $pager,
        ];
        return view('firm.firm_list', $data);
    }

    private function markers($firms, $city)
    {
        $result = ["type" => "FeatureCollection", "features" => []];
        foreach ($firms as $firm) {
            $result['features'][] =
                [
                    "type"       => "Feature",
                    "id"         => $firm->id,
                    "geometry"   =>
                        [
                            "type"        => "Point",
                            "coordinates" => [$firm->lat, $firm->lon]
                        ],
                    "properties" =>
                        [
                            "balloonContentHeader" => "<div><b><a target='_blank' href='" . route('firm', [$city->url, $firm->url]) . "'>{$firm->name}</a></b></div>",
                            "balloonContentBody"   => "<div class='text-muted'>{$firm->subtitle}</div>",
                            "balloonContentFooter" => "{$firm->address}",
                            "clusterCaption"       => "<strong>{$firm->name}</strong>",
                            "hintContent"          => "<strong>{$firm->subtitle} {$firm->name}</strong>"
                        ]
                ];

        }
        return response()->json($result);
    }


}
