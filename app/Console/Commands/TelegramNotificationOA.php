<?php

namespace App\Console\Commands;

use App\Services\OA\FlowNotificationService;
use Illuminate\Console\Command;

class TelegramNotificationOA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:telegram:notification:oa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @param FlowNotificationService $flowNotificationService
     */
    public function handle(FlowNotificationService $flowNotificationService)
    {
        $flowNotificationService->tester();

        $flowNotificationService->adminStaff();
    }
}
