<?php

namespace App\Components;

use VK\Client\VKApiClient;
use VK\Exceptions\Api\VKApiAccessException;

class Vk
{

    const VERSION = '5.92';
    const COUNT = 100;
    const TOKEN = '21166cb721166cb721166cb72a2171ed2b2211621166cb77d05191b5ad3af31e159c87a';

    /**
     * @var Core;
     */
    private $api;
    private $groupFields = ['city', 'contacts', 'counters', 'cover', 'description', 'market', 'members_count'];

    public function __construct()
    {
        $this->api = new VKApiClient(self::VERSION);
    }


    public function getPosts($wall, $count = 100, $offset = 0)
    {
        $params = [
            'owner_id' => "-{$wall}",
            'count'    => $count,
            'offset'   => $offset,
            'extended' => 1,
            'fields'   => $this->groupFields
        ];

        try {
            $posts = $this->api->wall()->get(self::TOKEN, $params);
        } catch (VKApiAccessException $exception) {
            $posts = [];
        }
        return $posts;
    }


    public function getPostComments($post, $offset = 0)
    {
        $args = [
            'post_id'  => $post->post_id,
            'owner_id' => "-" . $post->wall_id,
            'count'    => self::COUNT,
            'offset'   => $offset,
            'fields'   => 'has_photo,photo_100',
            'extended' => 1
        ];
        try {
            $result = $this->api->request('wall.getComments', $args)->fetchData();
        } catch (Error $exception) {
            return ['comments' => [], 'profiles' => []];
        }
        if ($result->count > self::COUNT && !$offset) {
            $n = ceil($result->count / self::COUNT);
            for ($i = 1; $i <= $n; $i++) {
                $data = $this->getPostComments($post, self::COUNT * $i);
                $result->items = array_merge($result->items, $data['comments']);
                $result->profiles = array_merge($result->profiles, $data['profiles']);
            }
        }
        return ['comments' => $result->items, 'profiles' => $result->profiles];
    }

    public function getIdByName($name)
    {
        $params = [
            'screen_name' => $name,
        ];
        $result = $this->api->utils()->resolveScreenName(self::TOKEN, $params);
        return array_get($result, 'object_id');
    }

}