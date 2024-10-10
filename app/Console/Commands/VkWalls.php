<?php

namespace App\Console\Commands;

use App\Components\Vk;
use App\Models\Contact;
use App\Models\VkWall;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;

class VkWalls extends Command
{
    protected $signature = 'vk:walls';
    protected $description = 'Parse vk walls';
    const STORAGE_WALLS_IMG = 'img-walls';

    /**
     * @var Vk
     */
    private $api;

    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $this->walls();
    }

    private function walls()
    {
        $this->api = new Vk();
        $walls = Contact::where('type', 'vkontakte')->groupBy('url')->get();
        foreach ($walls as $wall) {
            $w = VkWall::where('url', $wall->url)->first();
            if (!$w) {
                $name = explode('/', $wall->url);
                $name = array_last($name);
                $id = $this->api->getIdByName($name);
                if ($id) {
                    $posts = $this->api->getPosts($id, 1);
                    $group = array_get($posts, 'groups.0');
                    if ($group) {
                        $this->storeWall($wall->url, $group);
                        $this->info($wall->url);
                    }
                }
            }
        }
    }

    private function storeWall($url, $group)
    {
        $group['logos'] = $this->savePhotos($group);
        VkWall::firstOrcreate(['id' => $group['id']], [
            'id'       => $group['id'],
            'name'     => $group['name'],
            'url'      => $url,
            'data'     => $group,
            'index_at' => Carbon::yesterday()
        ]);
    }


    private function savePhotos($group)
    {
        $result = [];
        $client = new Client();
        foreach ($group as $key => $photo) {
            if (preg_match("/photo_/u", $key)) {
                try {

                } catch (ClientException $exception) {
                    $file = $client->get($photo)->getBody()->getContents();
                    $path = 'public/' . self::STORAGE_WALLS_IMG . "/{$group['id']}/{$key}.jpg";
                    if (\Storage::put($path, $file)) {
                        $result[] = $path;
                    }
                }
            }
        }
        return $result;
    }

}
