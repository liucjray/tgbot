<?php

namespace App\Services\OA;

use App\Repositories\OA\FlowViewRepository;
use App\Repositories\OA\LeaveViewRepository;
use App\Repositories\OA\UserRepository;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class FlowNotificationService
{
    private $flowViewRep;
    private $userRep;
    private $leaveViewRep;

    public function __construct(
        FlowViewRepository $flowViewRepository,
        UserRepository $userRepository,
        LeaveViewRepository $leaveViewRepository
    )
    {
        $this->flowViewRep = $flowViewRepository;
        $this->userRep = $userRepository;
        $this->leaveViewRep = $leaveViewRepository;
    }

    public function tester()
    {
        Log::debug(sprintf('%s: %s: %s', microtime(), __LINE__, '=== start ==='));
        $flows = $this->flowViewRep->getFlowMinAgo();
        Log::debug(sprintf('%s: %s: %s', microtime(), __LINE__, '=== fetch getFlowOneMinAgo finished ==='));
        if ($flows->count() > 0) {
            $tester = ['kelly'];
            foreach ($flows as $k=>$flow) {
                //符合以下條件才會通知
                $conds = [
                    //1. 測試人員
                    in_array($flow->station_user_name, $tester),
                    //2. 接收人員是第一站
                    $flow->station_sort == 1,
                    //3. 下午五點前才發
                    date('H') < 17,
                ];
                Log::debug(sprintf('%s: %s: %s', microtime(), __LINE__, "=== Process[$k][{$flow->id}] start ==="));
                $canSend = !in_array(false, $conds) === true;
                if ($canSend) {
                    Log::debug(sprintf('%s: %s: %s', microtime(), __LINE__, "=== sendMessage Process[$k][{$flow->id}] start ==="));
                    Telegram::sendMessage([
                        'chat_id' => env('CHAT_ID_TESTER'),
                        'text' => sprintf(
                            implode(PHP_EOL, [
                                '接收人: %s',
                                '接收時間: %s',
                                '發起人: %s',
                                '任務名稱: %s',
                                '任務網址: %s',
                            ]),
                            $flow->station_user_name,
                            $flow->station_create_at,
                            $flow->emp_no,
                            $flow->name,
                            sprintf('https://oaoa.tech/index.php?m=&c=Flow&a=read&id=%d', $flow->id)
                        )
                    ]);
                    Log::debug(sprintf('%s: %s: %s', microtime(), __LINE__, "=== sendMessage Process[$k][{$flow->id}] finished ==="));
                }
                Log::debug(sprintf('%s: %s: %s', microtime(), __LINE__, "=== Process[$k][{$flow->id}] end ==="));
            }
        }
        Log::debug(sprintf('%s: %s: %s', microtime(), __LINE__, '=== end ==='));
    }

    public function adminStaff()
    {
        $flows = $this->leaveViewRep->getFlowMinAgo();
        foreach ($flows as $flow) {
            Telegram::sendMessage([
                'chat_id' => env('CHAT_ID_ADMINSTAFF'),
                'text' => sprintf(
                    implode(PHP_EOL, [
                        '發起時間: %s',
                        '發起人員: %s',
                        '請假類別: %s',
                        '請假時數: %s',
                        '請假開始: %s',
                        '請假結束: %s',
                        '任務網址: %s',
                    ]),
                    date('Y-m-d H:i:s', $flow->create_time),
                    $flow->emp_no,
                    $flow->leave_type,
                    $flow->work_hour,
                    $flow->start_date,
                    $flow->end_date,
                    sprintf('https://oaoa.tech/index.php?m=&c=Flow&a=read&id=%d', $flow->id)
                )
            ]);
        }
    }

    public function tr2()
    {
        // 訊息
        Telegram::sendMessage([
            'chat_id' => env('CHAT_ID_TR2'),
            'text' => 'Teemo sucks my dick.'
        ]);

        // 照片
        Telegram::sendPhoto([
            'chat_id' => env('CHAT_ID_TR2'),
            'photo' => public_path('images/keep/mouse.jpg'),
            'caption' => 'sucks!!!'
        ]);
    }
}