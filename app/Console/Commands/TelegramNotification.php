<?php

namespace App\Console\Commands;

use App\Services\OA\FlowNotificationService;
use Illuminate\Console\Command;

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
     * @param FlowNotificationService $flowNotificationService
     */
    public function handle(FlowNotificationService $flowNotificationService)
    {
        $flowNotificationService->tr2();
    }
}
