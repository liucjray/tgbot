<?php

namespace App\Console\Commands;

use App\Services\TelegramNotification\TaiwanTestCentralNotificationService;
use Illuminate\Console\Command;

class TelegramNotificationEnglishGept extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:telegram:notification:english:gept';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '全民英檢字彙表';

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
     * @param \App\Services\TelegramNotification\TaiwanTestCentralNotificationService $taiwanTestCentralNotificationService
     */
    public function handle(TaiwanTestCentralNotificationService $taiwanTestCentralNotificationService)
    {
        $taiwanTestCentralNotificationService->send();
    }
}
