<?php

namespace App\Repositories\OA;

use App\Models\StockTarget;
use App\Repositories\Support\BaseRepository;

class StockTargetRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $app = app();
        parent::__construct($app);
    }

    public function model()
    {
        return StockTarget::class;
    }
}