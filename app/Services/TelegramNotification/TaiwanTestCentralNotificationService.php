<?php

namespace App\Services\TelegramNotification;

use Telegram\Bot\Laravel\Facades\Telegram;

class TaiwanTestCentralNotificationService
{
    protected $examSer;

    public function __construct()
    {
        $randomExamType = [
            app()->make('App\Services\English\TaiwanTestCentral\GeptService'),
            app()->make('App\Services\English\TaiwanTestCentral\CollegeEntranceExamService'),
        ];

        shuffle($randomExamType);

        $this->examSer = $randomExamType[0];
    }

    public function send()
    {
        $data = $this->examSer->getData();
        $idx = rand(0, count($data)-1);
        $word = $data[$idx];

        // 送出通知
        Telegram::sendMessage([
            'chat_id' => env('CHAT_ID_TR2_BOT'),
            'text' => implode(PHP_EOL, $word),
        ]);
    }
}