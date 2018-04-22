<?php

namespace App\Services\Stock;


use DiDom\Document;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;

class StockNotificationService
{
    private $totalPageCount;
    private $counter = 1;
    private $concurrency = 7;  // 同时并发抓取

    private $uri = 'https://tw.stock.yahoo.com/q/q?s=';
    private $stocks = [
        '0050',
        '0055',
        '2330',
    ];

    private $execStartTime; //程序開始執行時間
    private $execEndTime; //程序結束執行時間

    public function __construct()
    {
//        header("Content-Type:text/html; charset=big5");
    }

    private function _setExecStartTime()
    {
        $this->execStartTime = microtime(true);
    }
    private function _setExecEndTime()
    {
        $this->execEndTime = microtime(true);
    }

    public function index()
    {
        $this->_setExecStartTime();

        $client = new Client();

        $requests = function ($total) use ($client) {
            foreach ($this->stocks as $key => $stock) {

                $uri = $this->uri . $stock;
                yield function () use ($client, $uri) {
                    return $client->getAsync($uri);
                };
            }
        };

        $pool = new Pool($client, $requests($this->totalPageCount), [
            'concurrency' => count($this->stocks),
            'fulfilled'   => function ($response, $index) {

                $res = $response->getBody()->getContents();

                $this->_parseHtml($res);

                $this->countedAndCheckEnded();

            },
            'rejected'    => function ($reason, $index) {
                echo("rejected");
                echo("rejected reason: " . $reason);
                $this->countedAndCheckEnded();
            },
        ]);

        // 开始发送请求
        $promise = $pool->promise();
        $promise->wait();

        $this->_setExecEndTime();

        $this->showExecTime();
    }

    public function countedAndCheckEnded()
    {
        if ($this->counter < $this->totalPageCount) {
            $this->counter++;
            return;
        }
    }

    private function _parseHtml($html = '')
    {
        $document = new Document($html);
        $td = $document->find('html > body > center > table')[1]->find('td');

        $data = [
            '代號' => $this->_daihaoHandler($td[1]),
            '時間' => $td[2]->text(),
            '成交' => $td[3]->text(),
            '買進' => $td[4]->text(),
            '賣出' => $td[5]->text(),
            '漲跌' => $this->_zhangdieHandler($td[6]),
            '張數' => $td[7]->text(),
            '昨收' => $td[8]->text(),
            '開盤' => $td[9]->text(),
            '最高' => $td[10]->text(),
            '最低' => $td[11]->text(),
        ];

        dump($data);
    }

    private function _daihaoHandler($daihao)
    {
        return substr(trim($daihao->text()), 0, 4);
    }

    private function _zhangdieHandler($zhangdie)
    {
        $sign = strpos($zhangdie->html(), 'color="#ff0000"') ? '+' : '-';
        $number = trim($zhangdie->text());
        return sprintf('%s%s', $sign, $number);
    }

    public function showExecTime()
    {
        $info = sprintf(
            '總執行時間 (%s) = 結束執行時間 (%s) - 開始執行時間 (%s)',
            $this->execEndTime-$this->execStartTime,
            $this->execEndTime,
            $this->execStartTime
        );
        dump($info);
    }
}