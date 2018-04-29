<?php

namespace App\Services\English\TaiwanTestCentral;

class GeptService extends TaiwanTestCentralService
{
    protected $name = '全民英檢';
    protected $uri = 'http://www.taiwantestcentral.com/WordList/WordListByName.aspx?MainCategoryID=4&Letter=';
    protected $cacheKey = 'TaiwanTestCentral:GEPT';
}