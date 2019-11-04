<?php

declare(strict_types=1);

namespace App\Amqp\Producer;

use Hyperf\Amqp\Annotation\Producer;
use Hyperf\Amqp\Message\ProducerMessage;

/**
 * @Producer(exchange="chat", routingKey="single")
 */
class SyncSingleProducer extends ProducerMessage
{
    public function __construct($data)
    {
        dd($data);
        $this->payload = $data;
    }
}
