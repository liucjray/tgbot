<?php

namespace App\Console\Commands;

use App\Services\TelegramNotification\GegeNotificationService;
use Illuminate\Console\Command;

class TelegramNotificationJapanese50 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:telegram:notification:japanese50';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '日文50音';

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
     * @param \App\Services\TelegramNotification\GegeNotificationService $gegeNotificationService
     */
    public function handle(GegeNotificationService $gegeNotificationService)
    {
        $gegeNotificationService->send();
    }
}
