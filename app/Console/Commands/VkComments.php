<?php

namespace App\Console\Commands;

use App\Components\Vk;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class VkComments extends Command
{

    const STORAGE_COMMENTS_IMG = 'comments';
    const STORAGE_USER_LOGO = 'user';
    /**
     * @var Vk
     */
    private $api;

    protected $signature = 'vk:comments';
    protected $description = 'Parse vk comments';

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
        $this->api = new Vk();
        $this->info('Run parser');
        $this->parse();
    }

    private function parse()
    {
        $posts = Post::whereNotNull('vk_id')->get();
        foreach ($posts as $post) {
            $vk_post = $post->vkPost()->first();
            $data = $this->api->getPostComments($vk_post, 0);
            if ($data['comments']) {
                $comments = collect($data['comments']);
                $profiles = collect($data['profiles']);
                foreach ($comments as $vk_comment) {
                    $dbl = Comment::where([
                        'vk_wall_id' => $vk_post->wall_id,
                        'vk_post_id' => $vk_post->post_id,
                        'vk_id'      => $vk_comment->id,
                    ])->first();
                    if ($vk_comment->text && !$dbl) {
                        $parent = [];
                        $vk_user = $profiles->where('id', $vk_comment->from_id)->first();
                        $user = $this->createUser($vk_user);
                        $vk_parent = !empty($vk_comment->reply_to_comment) ? $vk_comment->reply_to_comment : null;
                        if ($vk_parent) {
                            $parent = Comment::where('vk_id', $vk_parent)
                                ->where('vk_wall_id', $vk_post->wall_id)
                                ->where('vk_post_id', $vk_post->post_id)
                                ->first();
                        }

                        $comment = [
                            'vk_id'        => $vk_comment->id,
                            'post_id'      => $post->id,
                            'user_id'      => $user->id,
                            'vk_post_id'   => $vk_post->post_id,
                            'vk_wall_id'   => $vk_post->wall_id,
                            'vk_user_id'   => $vk_comment->from_id,
                            'parent_id'    => !empty($parent->id) ? $parent->id : null,
                            'vk_parent_id' => $vk_parent,
                            'text'         => $vk_comment->text,
                            'created_at'   => $vk_comment->date,
                            'attachments'  => $this->attachments($vk_comment)
                        ];
                        Comment::create($comment);
                        $this->info('Add comment ' . str_limit($vk_comment->text, 10));
                    }
                }
            }
        }
    }

    /**
     * @param $vk_user
     * @return User
     */
    private function createUser($vk_user)
    {
        if (!$vk_user) {
            return User::where('id', 1)->first();
        }
        $user = User::where('vk_id', $vk_user->id)->first();
        if (!$user) {
            $client = new Client();
            $path = null;
            if ($vk_user->has_photo) {
                $logo = $client->get($vk_user->photo_100)->getBody()->getContents();
                $path = self::STORAGE_USER_LOGO . "/{$vk_user->id}/logo.jpg";
                \Storage::put('public/' . $path, $logo);
            }


            $vk_user = [
                'vk_id'     => $vk_user->id,
                'name'      => $vk_user->first_name,
                'last_name' => $vk_user->last_name,
                'email'     => "{$vk_user->id}@vk.com",
                'login'     => array_get($vk_user, 'screen_name'),
                'password'  => bcrypt(str_random(8)),
                'logo'      => $path,
            ];
            $user = User::create($vk_user);
        }
        return $user;
    }

    private function attachments($comment)
    {
        $result = null;
        $attachments = !empty($comment->attachments) ? $comment->attachments : [];
        foreach ($attachments AS $attachment) {
            $photos = [];
            if ($attachment->type == 'photo') {
                foreach ($attachment->photo as $key => $photo) {
                    if (preg_match("/photo_/u", $key)) {
                        $photos[] = $photo;
                    }
                }
                if ($photos) {
                    $client = new Client();
                    foreach ($photos as $n => $photo) {
                        $file = $client->get($photo)->getBody()->getContents();
                        $path = self::STORAGE_COMMENTS_IMG . "/{$attachment->photo->id}/{$n}.jpg";
                        if (\Storage::put('public/' . $path, $file)) {
                            $result[] = $path;
                        }
                    }
                }
            }
        }
        return $result;
    }
}
