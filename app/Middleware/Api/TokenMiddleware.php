<?php

declare(strict_types=1);

namespace App\Middleware\Api;

use App\Traits\Response;
use App\Utility\Token;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class TokenMiddleware
 * @package App\Middleware\Api
 */
class TokenMiddleware implements MiddlewareInterface
{
    use Response;
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    /**
     * @var array
     */
    protected $whiteList = [
        '/api/auth/login',
        '/api/auth/register',
    ];

    /**
     * TokenMiddleware constructor.
     * @param ContainerInterface $container
     * @param HttpResponse $response
     * @param RequestInterface $request
     */
    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 判断Token 验证token
        $params = $request->getParsedBody();
        $requestUri = $request->getUri()->getPath();
        // 忽略路由
        if (!in_array($requestUri, $this->whiteList)) {
            $token = $params['token'] ?? '';
            if (!$token) {
                return $this->response->json($this->fail("token不能为空"));
            }
            // 解密token
            $decodeToken = $this->container->get(Token::class)->decode($token);
            if ($decodeToken['status'] == 0) {
                return $this->response->json($this->fail($decodeToken['msg']));
            }
            $user = (array)$decodeToken['result']['data'];
            // 单点登陆处理
            $userToken = redis()->hGet('userToken', (string)$user['id']);
            if (!$userToken) {
                return $this->response->json($this->fail("请登录后进行操作."));
            }
            if ($token != $userToken) {
                return $this->response->json($this->fail("该账号已在别处登陆,请您重新登陆"));
            }
            setContext('userId', $user['id']);
        }
        return $handler->handle($request);
    }
}