<?php

declare(strict_types=1);

namespace App\Process;

use App\Model\GroupMessageModel;
use App\Model\UserMessageModel;
use App\Traits\PushMessage;
use Hyperf\Process\AbstractProcess;
use Swoole\Coroutine;
use Swoole\WebSocket\Server as WebSocketServer;

class PushMessageProcess extends AbstractProcess
{
    use PushMessage;

    public function handle(): void
    {
        /** @var WebSocketServer $server */
        $server = $this->container->get(WebSocketServer::class);
        while (true) {
            redis()->subscribe([getLocalIp()], function ($redis, $channel, $data) use ($server) {
                $pushData = json_decode($data, true);
                $fd = (int)$pushData['fd'];
                $senderId = $pushData['senderId'];
                $content = $pushData['content'];
                // 判断fd是否在线
                if ($server->isEstablished($fd)) {
                    $server->push($fd, json_encode($content));
                } else {
                    if (isset($pushData['userId'])) {
                        $data = [
                            'senderId' => $senderId,
                            'userId' => $pushData['userId'],
                            'content' => $content
                        ];
                        UserMessageModel::instance()->insert($data);
                    }
                    if (isset($pushData['groupId'])) {
                        $data = [
                            'senderId' => $senderId,
                            'groupId' => $pushData['groupId'],
                            'content' => $content
                        ];
                        GroupMessageModel::instance()->insert($data);
                    }
                }
            });
            Coroutine::sleep(0.5);
        }
    }
