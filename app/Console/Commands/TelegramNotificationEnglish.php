<?php

namespace App\Console\Commands;

use App\Services\TelegramNotification\TaiwanTestCentralNotificationService;
use Illuminate\Console\Command;

class TelegramNotificationEnglish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:telegram:notification:english';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '綜合字彙表(GEPT|CollegeEntranceExamService)';

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
