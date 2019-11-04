<?php

declare(strict_types=1);

namespace App\Exception;

use App\Utility\DingDing;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(StdoutLoggerInterface $logger, ContainerInterface $container)
    {
        $this->logger = $logger;
        $this->container = $container;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $msg = "- 文件名：**{$throwable->getFile()}** \n";
        $msg .= "- 第几行：**{$throwable->getLine()}** \n";
        $msg .= "- 错误信息：**{$throwable->getMessage()}**\n";
        $this->container->get(DingDing::class)->sendText($msg);
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());
        return $response->withStatus(500)->withBody(new SwooleStream('Internal Server Error.'));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
