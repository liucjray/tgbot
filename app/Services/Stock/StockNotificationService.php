<?php

namespace App\Services\Stock;


use DiDom\Document;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Support\Facades\Redis;
use Telegram\Bot\Laravel\Facades\Telegram;

class StockNotificationService
{
    private $totalPageCount;
    private $counter = 1;
    private $uri = 'https://tw.stock.yahoo.com/q/q?s=';

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

    public function getStocks()
    {
        return [
            '0050',
            '0055',
            '2330',
        ];
    }

    public function index()
    {
        $this->_setExecStartTime();

        $client = new Client();

        $requests = function ($total) use ($client) {
            foreach ($this->getStocks() as $key => $stock) {

                $uri = $this->uri . $stock;
                yield function () use ($client, $uri) {
                    return $client->getAsync($uri);
                };
            }
        };

        $pool = new Pool($client, $requests($this->totalPageCount), [
            'concurrency' => count($this->getStocks()), //併發抓取
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

        $this->sendNotification($data);
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

    /**
     * @param string $daihao
     * @param string $shijian
     * @return bool
     */
    public function needSend($daihao = '', $shijian = '')
    {
        if (!$daihao or !$shijian)
            return false;
        return Redis::get("stock:$daihao") !== $shijian ? true : false;
    }

    public function sendNotification($data = [])
    {
        $daihao = $data['代號'];
        $shijian = $data['時間'];

        if ($this->needSend($daihao, $shijian)) {
            // 寫入 KEY
            Redis::set("stock:$daihao", $shijian);

            // 送出通知
            Telegram::sendMessage([
                'chat_id' => env('CHAT_ID_RAY'),
                'text' => $this->_getNotificationMsgFromData($data)
            ]);
        }
    }

    private function _getNotificationMsgFromData($data = [])
    {
        if (empty($data))
            return '';

        $msg = [];
        foreach ($data as $desc => $datum) {
            $msg[] = sprintf("%s : %s", $desc, $datum);
        }

        return implode(PHP_EOL, $msg);
    }
}