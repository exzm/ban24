<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentPost;
use App\Models\Comment;

class CommentController extends Controller
{

    const BOT_TOKEN = '653604730:AAHQ1i_NHJjmUWYRXf08swtK1GGvALJU9-k';
    const CHAT_ID = '209733199';

    public function submitForm(CommentPost $comment)
    {
        $comment->merge(['ip' => $comment->getClientIp()]);
        $model = Comment::create($comment->all());

        $this->sendTelegram($model);

        if ($comment->ajax()) {
            return response()->json(
                ['view' => view('comment.comment', ['review' => $model])->render()]
            );
        } else {
            return redirect($this->getRedirectUrl());
        }
    }

    public function plus($id)
    {
        $comment = Comment::where('id', $id)->first();
        $comment->plus++;
        $comment->save();
        return $comment->plus;
    }

    public function minus($id)
    {
        $comment = Comment::where('id', $id)->first();
        $comment->minus++;
        $comment->save();
        return $comment->minus;
    }

    public function delete($id)
    {
        dd($id);
    }

    public function edit($id)
    {
        dd($id);
    }

    private function sendTelegram(Comment $comment)
    {
        $bot = new \TelegramBot\Api\BotApi(self::BOT_TOKEN);
        $message = "
*Новый отзыв*
{$comment->comment}
         
[" . \Request::server('HTTP_REFERER') . "](" . \Request::server('HTTP_REFERER') . ")

[Удалить](" . route('comment-delete', $comment->id) . ")
[Редактировать](" . route('comment-edit', $comment->id) . ")";

        return $bot->sendMessage(self::CHAT_ID, $message, 'Markdown');
    }

}
