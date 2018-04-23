<?php

namespace App\Services\English;

use DiDom\Document;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class TaiwanTestCentralService
{
    private $uri = 'http://www.taiwantestcentral.com/WordList/WordListByName.aspx?MainCategoryID=4&Letter=';

    public function getGeptData()
    {
        $html = $this->fetchHtml();
        return $this->parseHtml($html);
    }

    private function getAlphas()
    {
        return range('A', 'Z');
    }

    private function getRandomAlphas()
    {
        $index = rand(0, 25);
        return $this->getAlphas()[$index];
    }

    private function fetchHtml()
    {
        // get cache
        $randomAlphas = $this->getRandomAlphas();
        $cacheKey = "TaiwanTestCentral:$randomAlphas";
        $cacheByAlphas = Redis::get($cacheKey);
        if ($cacheByAlphas)
            return $cacheByAlphas;

        // fetch html
        $client = new Client();
        $html = (string) $client
            ->request('GET', $this->uri . $randomAlphas)
            ->getBody();

        // set cache
        Redis::set($cacheKey, $html);
        return $html;
    }

    private function parseHtml($html = '')
    {
        $rtn = [];
        $document = new Document($html);
        $trs = $document->find('.WordList')[0]->find('tr');
        foreach ($trs as $k => $tr) {
            // 第一個為欄位說明
            if ($k == 0) continue;

            $data = $tr->find('td');
            $rtn[] = [
                '級別 : ' . $data[1]->text(),
                '字詞 : ' . $data[3]->text(),
                '中文釋義 : ' . $data[5]->text(),
                '雅虎字典 : ' . sprintf('https://tw.dictionary.search.yahoo.com/search?p=%s', $data[3]->text()),
            ];
        }

        return $rtn;
    }
}