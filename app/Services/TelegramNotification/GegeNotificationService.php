<?php

namespace App\Services\TelegramNotification;

use App\Services\Japanese\GegeService;
use Telegram\Bot\Laravel\Facades\Telegram;

class GegeNotificationService
{
    protected $gegeSer;

    public function __construct(GegeService $gegeService)
    {
        $this->gegeSer = $gegeService;
    }

    public function send()
    {
        $data = $this->gegeSer->getData();
        $idx = rand(0, count($data)-1);

        list($word, $eg, $ex) = $data[$idx];

        $text = [
            "日文 : $word",
            "英文 : $eg",
            "例子 : $ex",
        ];

        // 送出通知
        Telegram::sendMessage([
            'chat_id' => env('CHAT_ID_JP_50'),
            'text' => implode(PHP_EOL, $text),
        ]);
    }
}