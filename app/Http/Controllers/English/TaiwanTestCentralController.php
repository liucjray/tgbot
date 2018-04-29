<?php

namespace App\Http\Controllers\English;

use App\Services\English\TaiwanTestCentral\CollegeEntranceExamService;
use App\Services\English\TaiwanTestCentral\GeptService;

class TaiwanTestCentralController
{
    protected $geptSer;
    protected $collegeEntranceExamSer;

    public function __construct(
        GeptService $geptService,
        CollegeEntranceExamService $collegeEntranceExam
    )
    {
        $this->geptSer = $geptService;
        $this->collegeEntranceExamSer = $collegeEntranceExam;
    }

    public function gept()
    {
        $r = $this->geptSer->getData();
        dump($r);
    }

    public function collegeEntranceExam()
    {
        $r = $this->collegeEntranceExamSer->getData();
        dump($r);
    }

    public function notification()
    {
        $c = app()->make('App\Services\TelegramNotification\TaiwanTestCentralNotificationService');
        $c->send();
    }
}