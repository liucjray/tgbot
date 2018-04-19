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
        $c = Carbon::now();
        $cMinAgo = $c->subMinute($interval);

        $r = $this->model
            ->where('is_del', 0)
            ->where('create_time', '>=', $cMinAgo->timestamp)
            ->get();
        return $r;
    }

}