<?php

namespace App\Http\Controllers\OA;

use App\Http\Controllers\Controller;
use App\Services\OA\FlowNotificationService;

class FlowNotificationController extends Controller
{

    private $flowNotificationSer;

    public function __construct(FlowNotificationService $flowNotificationService)
    {
        $this->flowNotificationSer = $flowNotificationService;
    }

    public function tester()
    {
        $this->flowNotificationSer->tester();
    }
}