<?php

namespace App\Http\Controllers;


class PageController extends Controller
{

    const BOT_TOKEN = '653604730:AAHQ1i_NHJjmUWYRXf08swtK1GGvALJU9-k';
    const CHAT_ID = '209733199';

    public function about()
    {

    }


    public function feedback()
    {
        $this->setTitle('Обратная связь');
        $this->setDescription('Обратная связь');

        $this->addBread(route('front'), 'Главная страница', 'Главная страница');
        $this->addBread(route('feedback-page'), 'Написать нам');

        if (request()->all()) {
            $this->sendTelegram(request()->all());
            return back()->with('success', 'Спасибо! Ваше сообщение отправлено');
        }
        return view('pages.feedback');
    }

    private function sendTelegram($data)
    {
        $bot = new \TelegramBot\Api\BotApi(self::BOT_TOKEN);

        $message = "
*Обратная связь*
{$data['text']}

{$data['name']}

{$data['email']}
         
[REFERER](" . \Request::server('HTTP_REFERER') . ")";

        return $bot->sendMessage(self::CHAT_ID, $message, 'Markdown');
    }

}
