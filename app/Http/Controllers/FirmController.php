<?php

namespace App\Http\Controllers;


use App\Models\City;
use App\Models\Firm;
use App\Models\Photo;
use ChrisKonnertz\OpenGraph\OpenGraph;
use Illuminate\Http\Request;
use LaravelQRCode\Facades\QRCode;

class FirmController extends Controller
{
    public function firmPage($city, $firm)
    {
        $city = City::where('url', '=', $city)->with('region', 'region.country')->firstOrFail();
        $firm = Firm::where('city_id', $city->id)
            ->where('url', $firm)
            ->with(['contacts', 'attributes', 'buildingInfo', 'gisGroups', 'gisGroups.group', 'comments', 'photos', 'city'])
            ->firstOrFail();

        $keywords = [];
        if ($firm->keywords->count() > 0) {
            mt_srand($firm->id);
            $keywords = $firm->keywords->unique('id')->toArray();
            $getMTRand = function () {
                return mt_rand();
            };
            $order = array_map($getMTRand, range(1, count($keywords)));
            array_multisort($order, $keywords);
            mt_srand();
        }

        $posts = $firm->VkPosts ? $firm->VkPosts->get() : collect();
        $wall = $posts->count() > 0 ? $posts->first()->wall : null;

        $this->addJs('//api-maps.yandex.ru/2.1.65/?lang=ru-RU');
        $this->addJs('barrating.min.js');
        $this->addJs('firm.js');

        $this->setTitle("{$firm->name} {$city->in()}, график работы, проезд, контакты и цены на услуги.");
        $this->setDescription("{$firm->name}, {$firm->address} - новости, отзывы и фотографии " . ($firm->subtitleCases ? $firm->subtitleCases->caseEd(RU_RO) : 'компании').". Часы работы {$firm->StrWorktime}");
        if (!empty($wall->description)) {
            $this->setDescription($wall->description);
        }

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

        $data = [
            'city'    => $city,
            'og'      => $og,
            'firm'    => $firm,
            'keys'    => array_slice($keywords, 0, 6),
            'reviews' => $firm->comments,
            'posts'   => $posts,
            'wall'    => $wall,
            'near'    => $firm->near(5),
            'footer'  => "{$firm->name} {$city->in()}"
        ];
        return view('firm.firm', $data);
    }


    public function rating($firm, Request $request)
    {
        $firm = Firm::where('id', $firm)->firstOrFail();
        $firm->increment('rating_value', $request->get('score', 0));
        $firm->increment('rating_count');
        return response()->json(['code' => '200']);
    }

    public function qrcodePage($firm)
    {
        $firm = Firm::where('id', $firm)->firstOrFail();
        return view('popup.qrcode', ['firm' => $firm]);
    }

    public function qrcode($firm)
    {
        $firm = Firm::where('id', $firm)->with('city', 'contacts', 'buildingInfo', 'street')->firstOrFail();

        $firstName = $firm->name;
        $lastName = $firm->subtitle ?: '';
        $email = $firm->emails->count() ? $firm->emails->first()->value : '';

        $address = [
            'type'    => 'home',
            'pref'    => true,
            'street'  => $firm->address,
            'city'    => $firm->city->name,
            'state'   => '',
            'country' => 'Россия',
            'zip'     => data_get($firm->buildingInfo, 'postal', ''),
        ];
        $addresses = [$address];
        $phones = [];
        foreach ($firm->phones as $phone) {
            $phones[] = [
                'type'      => $phone->comment,
                'number'    => $phone->text,
                'cellPhone' => true
            ];
        }

        $img = QRCode::vCard($firstName, $lastName, '', $email, $addresses, $phones)
            ->setErrorCorrectionLevel('H')
            ->setSize(12)
            ->setMargin(2)
            ->svg();

        return response($img)->header('Content-Type', 'image/png');

    }

    public function sendSms($firm)
    {
        $firm = Firm::where('id', $firm)->firstOrFail();
        return view('popup.send-sms', ['firm' => $firm]);
    }

    public function myFirm($firm)
    {
        $firm = Firm::where('id', $firm)->firstOrFail();
        return view('popup.my-firm', ['firm' => $firm]);
    }

    public function error($firm)
    {
        $firm = Firm::where('id', $firm)->firstOrFail();
        return view('popup.error-firm', ['firm' => $firm]);
    }

    public function route($firm)
    {
        $firm = Firm::where('id', $firm)->firstOrFail();
        return view('popup.route-firm', ['firm' => $firm]);
    }

    public function uploadPhoto($firm, Request $request)
    {
        $firm = Firm::where('id', $firm)->firstOrFail();

        $this->validate($request, [
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10000',
        ]);

        if ($request->hasfile('files')) {
            $data = [];
            foreach ($request->file('files') as $image) {
                $name = $image->getClientOriginalName();
                $image->move(public_path() . "/photos/{$firm->url}", $name);
                $data[] = ['firm_id' => $firm->id, 'url' => "/photos/{$firm->url}/$name"];
            }
            Photo::insert($data);
            return back()->with('success', 'Спасибо, фотографии добавлены!');
        }

        return view('popup.upload-photo', ['firm' => $firm]);
    }
}
