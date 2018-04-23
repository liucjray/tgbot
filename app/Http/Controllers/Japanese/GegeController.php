<?php

namespace App\Http\Controllers\Japanese;

use App\Http\Controllers\Controller;
use App\Services\Japanese\GegeService;
use App\Services\TelegramNotification\GegeNotificationService;

class GegeController extends Controller
{

    private $gegeNotificationSer;

    public function __construct(GegeNotificationService $gegeNotificationService)
    {
        $this->gegeNotificationSer = $gegeNotificationService;
    }

    public function index()
    {
        $this->gegeNotificationSer->send();
    }
}