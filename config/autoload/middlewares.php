<?php

declare(strict_types=1);


use App\Middleware\CorsMiddleware;

return [
    'http' => [
        CorsMiddleware::class
    ],
];
