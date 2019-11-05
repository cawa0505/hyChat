<?php

declare(strict_types=1);

use Hyperf\WebSocketServer\Exception\Handler\WebSocketExceptionHandler;

return [
    'handler' => [
        'http' => [
            App\Exception\Handler\ValidationExceptionHandler::class,
            App\Exception\Handler\AppExceptionHandler::class,

        ],
        'ws' => [
            WebSocketExceptionHandler::class
        ],
    ],
];
