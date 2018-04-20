<?php

namespace App\Repositories\OA;

use App\Models\XiaoweiFlowView;
use App\Models\XiaoweiLeaveView;
use Carbon\Carbon;

class LeaveViewRepository
{
    protected $model;

    public function __construct(XiaoweiLeaveView $xiaoweiLeaveView)
    {
        $this->model = $xiaoweiLeaveView;
    }

    public function getFlowMinAgo($interval = 1)
    {
        $start = Carbon::createFromTime(date('H'), date('i'), 0)->subMinute($interval)->timestamp;
        $end = Carbon::createFromTime(date('H'), date('i'), 0)->subSecond()->timestamp;

        $r = $this->model
            ->where('is_del', 0)
            ->whereBetween('create_time', [$start, $end])
            ->get();
        return $r;
    }

}