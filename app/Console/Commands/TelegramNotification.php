<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:telegram:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Telegram Notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $response = Telegram::sendMessage([
            'chat_id' => env('CHAT_ID_TR2'),
            'text' => 'Teemo 每分鐘蹲下來含'
        ]);
        $messageId = $response->getMessageId();
    }
}
