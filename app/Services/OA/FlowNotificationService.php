<?php

namespace App\Services\OA;

use App\Repositories\OA\FlowViewRepository;
use App\Repositories\OA\UserRepository;
use Telegram\Bot\Laravel\Facades\Telegram;

class FlowNotificationService
{
    private $flowViewRep;
    private $userRep;

    public function __construct(
        FlowViewRepository $flowViewRepository,
        UserRepository $userRepository
    ) {
        $this->flowViewRep = $flowViewRepository;
        $this->userRep = $userRepository;
    }

    public function tester()
    {
        $flows = $this->flowViewRep->getFlowOneMinAgo();

        if ($flows->count() > 0) {
            $tester = $this->userRep->getTester()->pluck('emp_no')->all();
            $tester[] = 'ray';
            foreach ($flows as $flow) {
                if (in_array($flow->emp_no, $tester)) {
                    Telegram::sendMessage([
                        'chat_id' => env('CHAT_ID_TR2'),
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
                }
            }
        }

    }
}