<?php

declare(strict_types=1);

namespace App\Listener;

use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Str;

/**
 * Class DbQueryListener
 * @package App\Listener
 */
class DbQueryListener implements ListenerInterface
{
    /**
     * @return array
     */
    public function listen(): array
    {
        return [
            QueryExecuted::class,
        ];
    }

    /**
     * @param object $event
     */
    public function process($event)
    {
        if ($event instanceof QueryExecuted) {
            $sql = $event->sql;
            if (!Arr::isAssoc($event->bindings)) {
                foreach ($event->bindings as $key => $value) {
                    $sql = Str::replaceFirst('?', "'{$value}'", $sql);
                }
            }
            logger('sql')->debug(sprintf('[%s] %s', $event->time, $sql));
            stdout()->info(sprintf('[%s] %s', $event->time, $sql));
        }
    }
}
