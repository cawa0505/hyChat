<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ValidationExceptionHandler extends \Hyperf\Validation\ValidationExceptionHandler
{
    protected $response;

    public function __construct(\Hyperf\HttpServer\Contract\ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();
        /** @var ValidationException $throwable */
        $body = $throwable->validator->errors()->first();
        return $this->response->json([
            'code' => $throwable->status,
            'message' => $body,
        ])->withStatus($throwable->getCode());
    }
}
