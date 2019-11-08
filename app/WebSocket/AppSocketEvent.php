<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/9/30
 * Time: 10:34
 */

namespace App\WebSocket;

use App\Utility\Token;
use App\WebSocket\Service\UserService;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use ReflectionClass;
use ReflectionException;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class AppSocketEvent
 * @package App\WebSocket
 */
class AppSocketEvent implements OnOpenInterface, OnMessageInterface, OnCloseInterface
{
    /**
     * @var
     */
    private $loginType;

    /**
     * @param Server $server
     * @param Request $request
     */
    public function onOpen(Server $server, Request $request): void
    {
        $params = $request->get;
        dd($params);
        if (!isset($params['token']) || !$params['token']) {
            stdout()->info('token为空');
            $server->close($request->fd);
            return;
        }
        if ($params['token'] == "system") {
            $server->push($request->fd, 'welcome to you');
            return;
        }
        $tokenData = container()->get(Token::class)->decode($params['token']);
        if ($tokenData['status'] == 0) {
            $server->push($request->fd, $tokenData['msg']);
            $server->close($request->fd);
            return;
        }
        $userInfo = (array)$tokenData['result']['data'];
        $this->loginType = $userInfo['login_type'];
        // 将fd和用户id绑定
        $server->bind($request->fd, $userInfo['id']);
        //设置userId关联的fd
        /** @var UserService $userService */
        $userService = container()->get(UserService::class);
        $fdInfo = [
            'ip' => getLocalIp(),
            'port' => env("SOCKET_PORT", 9502),
            'fd' => $request->fd
        ];
        $userService->setUserFd($userInfo['id'], json_encode($fdInfo), $this->loginType);
        $server->push($request->fd, 'welcome to you');
    }

    /**
     * 接收消息
     * @param Server $server
     * @param Frame $frame
     */
    public function onMessage(Server $server, Frame $frame): void
    {
        $data = $frame->data;
        if ($data == 'PING') {
            $server->push($frame->fd, 'PONG');
            return;
        }
        $data = json_decode($data, true);
        if (!is_array($data)) {
            $server->push($frame->fd, "decode message error!");
            return;
        }
        $controller = '\\App\\WebSocket\\Controller\\' . ucfirst($data['controller'] . 'Controller');
        $action = $data['action'];
        $params = [];
        if (!empty($data['content'])) {
            $content = $data['content'];
            $params = is_array($content) ? $content : ['content' => $content];
        }
        try {
            if (!class_exists($controller)) {
                $server->push($frame->fd, "controller {$controller} not found");
                return;
            }
            $ref = new ReflectionClass($controller);
            if (!$ref->hasMethod($action)) {
                $server->push($frame->fd, "class {$controller} action {$action} not found");
                return;
            }
            $controllerObj = new $controller($server, $frame, $params, $this->loginType);
            $controllerObj->$action();
        } catch (ReflectionException $exception) {
            stdout()->error($exception->getMessage());
            logger()->error($exception->getMessage());
        }
    }

    /**
     * 用户关系连接
     * @param \Swoole\Server $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(\Swoole\Server $server, int $fd, int $reactorId): void
    {
        $info = $server->connection_info($fd);

        if (isset($info['websocket_status']) && $info['websocket_status'] !== 0) {
            // 获取fd关联的uid
            $fdInfo = $server->getClientInfo($fd);
            $userId = isset($fdInfo['uid']) ?? 0;
            if ($userId) {
                /** @var UserService $userService */
                $userService = container()->get(UserService::class);
                // 删除fd关联的userId
                $userService->deleteUserFd($userId, $this->loginType);
            }
        }
    }
}