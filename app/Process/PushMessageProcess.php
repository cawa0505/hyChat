<?php

declare(strict_types=1);

namespace App\Process;

use Hyperf\Process\AbstractProcess;
use Swoole\Coroutine;

class PushMessageProcess extends AbstractProcess
{
    public function handle(): void
    {
        $redis = redis();
        $server = getServer();
        while (true) {
            $redis->subscribe((array)getLocalIp(), function ($redis, $channel, $data) use ($server) {
                $data = json_decode($data, true);
                $server = getServer();
                $fd = (int)$data['fd'];
                if ($server->isEstablished($fd)) {
                    $server->push($fd, $data['data']);
                }
            });
            Coroutine::sleep(0.5);
        }
    }
}
