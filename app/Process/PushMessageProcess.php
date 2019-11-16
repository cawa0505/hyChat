<?php

declare(strict_types=1);

namespace App\Process;

use Hyperf\Process\AbstractProcess;
use Swoole\Coroutine;
use Swoole\WebSocket\Server;

class PushMessageProcess extends AbstractProcess
{
    public function handle(): void
    {
        $redis = redis();
        /** @var Server $server */
        $server = server();
        while (true) {
            $redis->subscribe([getLocalIp()], function ($redis, $channel, $data) use ($server) {
                $pushData = json_decode($data, true);
                $fd = (int)$pushData['fd'];
                if ($server->isEstablished($fd)) {
                    $server->push($fd, json_encode($pushData['data']));
                }
            });
            Coroutine::sleep(0.5);
        }
    }
}
