<?php

namespace App\Services\Japanese;

use DiDom\Document;
use GuzzleHttp\Client;

class GegeService
{
    public function getData()
    {
        $html = $this->fetchHtml();
        $data = $this->parseHtml($html);
        return $data;
    }

    private function fetchHtml()
    {
        $client = new Client();
        return (string) $client
            ->request('GET', 'https://gege.kyjhome.com/50jp.html')
            ->getBody();
    }

    private function parseHtml($html = '')
    {
        $rtn = $word = $eg = $ex = [];

        $document = new Document($html);
        $boxes = $document->find('.jp50-box');
        foreach ($boxes as $box) {
            $word = $box->find('.jp-tone-word')[0]->text(); //日文字母
            $eg = $box->find('.jp-tone-eg')[0]->text(); //羅馬拼音
            $ex = $box->find('.jp-tone-ex')[0]->text(); //單字例子

            if ($word !== '-') {
                $rtn[] = [$word, $eg, $ex];
            }
        }

        return $rtn;
    }
}