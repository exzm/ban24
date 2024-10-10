<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupFilter;
use App\Models\City;
use App\Models\Firm;
use App\Models\Group;
use App\Models\GroupFirm;
use ChrisKonnertz\OpenGraph\OpenGraph;

class GroupController extends Controller
{
    private const PER_PAGE = 60;

    public function groupPage($city, $group)
    {
        $city = City::where('url', $city)->firstOrFail();
        $group = Group::where('url', $group)
            ->with(['gisGroups'])
            ->firstOrFail();


        $firms = GroupFirm::with(['firm', 'firm.city', 'firm.contacts'])
            ->where('city_id', $city->id)
            ->whereIn('gis_group_id', $group->gisGroups->pluck('id'))
            ->paginate(self::PER_PAGE);
        $count = $firms->total();
        $pager = $firms->links();
        $firms = $firms->pluck('firm');

        $img = $this->getImg($firms, $city, '650,450');

        $this->setTitle("{$group->name} {$city->in()}, {$count} компан" . ending($count, ['ия', 'ии', 'ий']) . " на карте с контактами и отзывами.");
        $this->setDescription("Найдено {$count} компан" . ending($count, ['ия', 'ии', 'ий']) . " {$city->in()}. Вы легко сможете найти работающие рядом с вами компании из категории «{$group->name}» на карте.");
        $this->addJs('//api-maps.yandex.ru/2.1.65/?lang=ru-RU');

        $og = new OpenGraph();
        $og->title($this->title)
            ->type('article')
            ->image($img)
            ->locale('ru_RU')
            ->description($this->description)
            ->url($this->canonical);


        $this->addBread(route('city', [$city->url]), $city->name, $city->name);
        $this->addBread(route('group', [$city->url, $group->url]), $group->name, $group->name);

        $data = [
            'city'     => $city,
            'og'       => $og,
            'count'    => $count,
            'group'    => $group,
            'img'      => $img,
            'firms'    => $firms,
            'pager'    => $pager,
            'comments' => $group->comments($city, 6)->with(['firm'])->get(),
            'footer'   => "{$group->gisGroups->first()->name} {$city->in()}"
        ];
        return view('group.group', $data);
    }

    public function filter($city, $group, GroupFilter $request)
    {
        $city = City::where('url', $city)->firstOrFail();
        $group = Group::where('url', $group)
            ->with(['gisGroups'])
            ->firstOrFail();

        $firms = GroupFirm::where('city_id', $city->id)
            ->whereIn('gis_group_id', $group->gisGroups->pluck('id'))
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

        if ($request->route()->getName() == 'group-markers') {
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
