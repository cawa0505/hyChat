<?php

declare(strict_types=1);

namespace App\Amqp\Consumer;

use Hyperf\Amqp\Result;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\DbConnection\Db;

/**
 * @Consumer(exchange="chat", routingKey="single", queue="singleMessage", name ="SyncSingleConsumer", nums=1)
 */
class SyncSingleConsumer extends ConsumerMessage
{
    public function consume($data): string
    {
        dd($data);
        Db::beginTransaction();
        Db::rollBack();
        Db::commit();
        return Result::ACK;
    }
}
