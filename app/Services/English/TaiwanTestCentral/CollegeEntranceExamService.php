<?php

namespace App\Services\English\TaiwanTestCentral;

class CollegeEntranceExamService extends TaiwanTestCentralService
{
    protected $name = '學科能力測驗';
    protected $uri = 'http://www.taiwantestcentral.com/WordList/WordListByName.aspx?MainCategoryID=17&Letter=';
    protected $cacheKey = 'TaiwanTestCentral:CollegeEntranceExamService';
}