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

    public function getFlowOneMinAgo()
    {
        $c = Carbon::now();
        $cOneMinAgo = $c->subMinute();

        $r = $this->model
            ->where('is_del', 0)
            ->where('station_create_at', '>=', $cOneMinAgo)
            ->get();
        return $r;
    }
}