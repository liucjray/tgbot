<?php

namespace App\Repositories\OA;

use App\Models\XiaoweiFlowView;
use Carbon\Carbon;

class FlowViewRepository
{
    protected $model;

    public function __construct(XiaoweiFlowView $flowView)
    {
        $this->model = $flowView;
    }

    public function getFlowMinAgo($interval = 1)
    {
        $YmdHis = 'Y-m-d H:i:s';
        $start = Carbon::createFromTime(date('H'), date('i'), 0)->subMinute($interval)->format($YmdHis);
        $end = Carbon::createFromTime(date('H'), date('i'), 0)->subSecond()->format($YmdHis);

        $r = $this->model
            ->where('is_del', 0)
            ->whereBetween('station_create_at', [$start, $end])
            ->get();
        return $r;
    }
}