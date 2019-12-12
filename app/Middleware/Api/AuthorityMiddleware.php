<?php

declare(strict_types=1);

namespace App\Middleware\Api;

use App\Constants\ApiCode;
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
 * Class AuthorityMiddleware
 * @package App\Middleware\Api
 */
class AuthorityMiddleware implements MiddlewareInterface
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
        '/admin/auth/login',
        '/admin/auth/logout',
    ];

    /**
     * TokenMiddleware constructor.
     * @param HttpResponse $response
     * @param RequestInterface $request
     */
    public function __construct(HttpResponse $response, RequestInterface $request)
    {
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
        $token = $request->getHeader('Authorization')[0] ?? '';
        $requestUri = $request->getUri()->getPath();
        // 忽略路由
        if (!in_array($requestUri, $this->whiteList)) {
            if (!$token) {
                return $this->response->json($this->fail(ApiCode::TOKEN_NOT_EXIST));
            }
            // 解密token
            $decodeToken = $this->container->get(Token::class)->decode($token);

            if ($decodeToken['status'] == 0) {
                return $this->response->json($this->fail(ApiCode::TOKEN_SIGN_ERROR));
            }
            $admin = (array)$decodeToken['result']['data'];
            // 单点登陆处理
            $userToken = redis()->hGet('adminToken', $admin['id']);
            if (!$userToken) {
                return $this->response->json($this->fail(ApiCode::NOT_LOGIN));
            }
            if ($token != $userToken) {
                return $this->response->json($this->fail(ApiCode::RENEW_LOGIN));
            }
            setContext('adminId', $admin['id']);
            $routeResult = $this->check($requestUri);
            if ($routeResult['code'] != 200) {
                return $this->response->json($this->fail($routeResult['code'],$routeResult['message']));
            }
        }
        return $handler->handle($request);
    }

    /**
     * 检测路由
     * @param $route
     * @return array|bool
     */
    private function check($route)
    {
        if (!$route) {
            return $this->fail(151, "路由地址不能为空");
        }
        // 当前路由统一使用小写
        $route = strtolower($route);
        // 获取用户权限
        $adminId = getContext('adminId');
        if ($adminId == 1) {
            return $this->success();
        }
        $permission = redis()->hGet('admin_permission', $adminId);
        $permission = json_decode($permission, true);
        $permission = array_merge($permission, $this->whiteList);
        if (empty($permission)) {
            return $this->fail(152, "权限为空");
        }
        if (!in_array($route, $permission)) {
            return $this->fail(153, "没有操作权限");
        }
        return true;

    }
}