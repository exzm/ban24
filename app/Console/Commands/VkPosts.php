<?php

namespace App\Console\Commands;

use App\Components\Vk;
use App\Models\VkPhotos;
use App\Models\VkPost;
use App\Models\VkWall;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class VkPosts extends Command
{
    protected $signature = 'vk:posts';
    protected $description = 'Parse vk posts';
    const STORAGE_POSTS_IMG = 'img-posts';

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
        $this->posts();
    }


    private function posts()
    {
        $this->api = new Vk();
        $walls = VkWall::whereDate('index_at', '<', Carbon::yesterday())->get();
        foreach ($walls AS $wall) {
            $this->info($wall->url);
            $posts = $this->api->getPosts($wall->id, 5);
            $this->storePosts($wall, array_get($posts, 'items', []));
            $wall->index_at = Carbon::now();
            $wall->save();
        }
    }

    private function storePosts(VkWall $wall, $posts)
    {
        foreach ($posts as $post) {
            $vkPost = VkPost::where('post_id', $post['id'])
                ->where('wall_id', $wall->id)
                ->first();
            if (!$vkPost && $this->validPost($post)) {
                $newPost = vkPost::create([
                    'wall_id'    => $wall->id,
                    'post_id'    => $post['id'],
                    'data'       => $post,
                    'created_at' => $post['date']
                ]);
                $this->savePhotos(array_get($post, 'attachments', []), $newPost->id, $wall->id);

            }
        }
    }

    private function savePhotos($photos, $post_id, $wall_id)
    {
        $client = new Client();
        foreach ($photos as $id => $photo) {
            if ($photo['type'] == 'photo') {
                $n = 0;
                foreach (array_get($photo, 'photo.sizes', []) as $size) {
                    $n++;
                    if (!in_array($size['type'], ['m', 'q'])) continue;
                    $file = $client->get($size['url'])->getBody()->getContents();
                    $path = self::STORAGE_POSTS_IMG . "/{$wall_id}/{$post_id}-{$id}-{$n}.jpg";
                    if (\Storage::put('public/' . $path, $file)) {
                        VkPhotos::insert([
                            'vk_id'   => $photo['photo']['id'],
                            'post_id' => $post_id,
                            'size'    => $size['type'],
                            'path'    => '/storage/' . $path
                        ]);
                    }
                }
            }
        }
    }

    private function validPost($post)
    {
        if (!$post['text']) return false;
        if (array_get($post, 'attachments.0.type') == 'video') return false;
        if ($post['from_id'] != $post['owner_id']) return false;
        if ($post['post_type'] != 'post') return false;
        return true;
    }
}
