<?php

namespace App\Repositories\OA;

use App\Models\XiaoweiFlowView;
use App\Models\XiaoweiUser;

class UserRepository
{
    protected $model;

    public function __construct(XiaoweiUser $xiaoweiUser)
    {
        $this->model = $xiaoweiUser;
    }

    public function getTester()
    {
        return $this->model
            ->where('dept_id', 58)
            ->get();
    }
}