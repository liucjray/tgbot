<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Services\Stock\StockNotificationService;
use Illuminate\Support\Facades\Redis;

class StockNotificationController extends Controller
{

    private $stockNotificationSer;

    public function __construct(StockNotificationService $stockNotificationService)
    {
        $this->stockNotificationSer = $stockNotificationService;
    }

    public function index()
    {
        $this->stockNotificationSer->index();
    }
}