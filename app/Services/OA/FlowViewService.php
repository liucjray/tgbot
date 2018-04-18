<?php

namespace App\Services\OA;

use App\Repositories\OA\FlowViewRepository;

class FlowViewService
{
    private $flowViewRep;

    public function __construct(FlowViewRepository $flowViewRepository)
    {
        $this->flowViewRep = $flowViewRepository;
    }
}