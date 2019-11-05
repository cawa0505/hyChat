<?php

declare(strict_types=1);


use App\Middleware\Api\DecryptMiddleware;
use App\Middleware\CorsMiddleware;
use Hyperf\Validation\Middleware\ValidationMiddleware;

return [
    'http' => [
        CorsMiddleware::class,
        DecryptMiddleware::class,
        ValidationMiddleware::class
    ],
];
