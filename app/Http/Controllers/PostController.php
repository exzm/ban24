<?php

namespace App\Http\Controllers;


use App\Models\City;
use App\Models\Firm;
use ChrisKonnertz\OpenGraph\OpenGraph;

class PostController extends Controller
{
    private const PER_PAGE = 10;

    public function postsPage($city, $firm)
    {
        $city = City::where('url', '=', $city)->with('region', 'region.country')->firstOrFail();
        $firm = Firm::where('city_id', $city->id)
            ->where('url', $firm)
            ->with(['contacts'])
            ->firstOrFail();
        $posts = $firm->VkPosts->paginate(self::PER_PAGE);
        $wall = $firm->VkPosts->first()->wall;

        $this->addCss('featherlight.min.css');
        $this->addCss('featherlight.gallery.min.css');
        $this->addJs('featherlight.min.js');
        $this->addJs('featherlight.gallery.min.js');

        $og = new OpenGraph();
        $og->title($this->title)
            ->type('article')
            ->image($firm->getImg())
            ->locale('ru_RU')
            ->description($this->description)
            ->url($this->canonical);

        $this->addBread(route('city', [$city->url]), $city->name, $city->name);
        $this->addBread(route('group', [$city->url, $firm->groups->first()->url]), $firm->gisGroups->first()->name, $firm->gisGroups->first()->name);
        $this->addBread(route('firm', [$city->url, $firm->url]), $firm->name, $firm->name);
        $this->addBread(route('firm-posts', [$city->url, $firm->url]), 'Новости', 'Новости');

        if ($posts->currentPage() > 1) {
            $this->addBread(route('firm-posts', [$city->url, $firm->url]), "Страница {$posts->currentPage()}", "Новости {$posts->currentPage()}");
        }

        $this->setTitle("{$wall->name}");
        $this->setDescription("{$wall->name} {$city->in()}. Последние новости компании {$firm->name}. Всего {$posts->total()} новост" . ending($posts->total(), ['ь', 'и', 'ей']));

        $data = [
            'city'   => $city,
            'og'     => $og,
            'firm'   => $firm,
            'posts'  => $posts,
            'wall'   => $wall,
            'footer' => "{$wall->name}"
        ];
        return view('post.post', $data);
    }
}
