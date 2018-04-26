<?php

namespace App\Services\TelegramNotification;

use App\Services\English\TaiwanTestCentralService;
use App\Services\Japanese\GegeService;
use Telegram\Bot\Laravel\Facades\Telegram;

class TaiwanTestCentralNotificationService
{
    protected $taiwanTestCentralSer;

    public function __construct(TaiwanTestCentralService $taiwanTestCentralService)
    {
        $this->taiwanTestCentralSer = $taiwanTestCentralService;
    }

    public function send()
    {
        $data = $this->taiwanTestCentralSer->getGeptData();
        $idx = rand(0, count($data)-1);
        $word = $data[$idx];

        // 送出通知
        Telegram::sendMessage([
            'chat_id' => env('CHAT_ID_TR2_BOT'),
            'text' => implode(PHP_EOL, $word),
        ]);
    }
}