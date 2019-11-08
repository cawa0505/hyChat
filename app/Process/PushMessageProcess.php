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
            $redis->subscribe((array)getLocalIp(), function ($redis, $channel, $data) use ($server) {
                $data = json_decode($data, true);
                $fd = (int)$data['fd'];
                if ($server->isEstablished($fd)) {
                    $server->push($fd, $data['data']);
                }
            });
            Coroutine::sleep(0.5);
        }
    }
}
