<?php

namespace App\Console\Commands;

use App\Services\OA\FlowNotificationService;
use App\Services\Stock\StockNotificationService;
use Illuminate\Console\Command;

class TelegramNotificationStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:telegram:notification:stock';

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
     * @param StockNotificationService $stockNotificationService
     */
    public function handle(StockNotificationService $stockNotificationService)
    {
        $stockNotificationService->index();
    }
}
