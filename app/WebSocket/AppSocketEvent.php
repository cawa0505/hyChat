<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/9/30
 * Time: 10:34
 */

namespace App\WebSocket;

use App\Utility\Token;
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
        $server->getClientInfo($request->fd);
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
        /** @var Common $userService */
        $common = container()->get(Common::class);
        $fdInfo = [
            'ip' => getLocalIp(),
            'port' => env("SOCKET_PORT", 9502),
            'fd' => $request->fd
        ];
        stdout()->info(json_encode($fdInfo));
        $common->setUserFd($userInfo['id'], json_encode($fdInfo), $this->loginType);
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
        dd($data);
        if ($data == 'PING') {
            $server->push($frame->fd, 'PONG');
            return;
        }
        $data = json_decode($data, true);
        if (!is_array($data)) {
            $server->push($frame->fd, "decode message error!");
            return;
        }
        $class = '\\App\\WebSocket\\Controller\\' . ucfirst($data['controller']);
        $action = $data['action'];
        $params = [];
        if (!empty($data['content'])) {
            $content = $data['content'];
            $params = is_array($content) ? $content : ['content' => $content];
        }
        try {
            if (!class_exists($class)) {
                $server->push($frame->fd, "class {$class} not found");
                return;
            }
            $ref = new ReflectionClass($class);
            if (!$ref->hasMethod($action)) {
                $server->push($frame->fd, "class {$class} action {$action} not found");
                return;
            }
            $controller = new $class($server, $frame, $params, (int)$this->loginType);
            $controller->$action();
        } catch (ReflectionException $exception) {
            stdout()->error($exception->getMessage());
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
            /** @var Common $userService */
            $common = container()->get(Common::class);
            // 获取fd关联的uid
            $userId = $common->getFdUser($fd);
            if ($userId) {
                // 删除fd关联的userId
                $common->deleteUserFd($userId, $this->loginType);
            }
        }
    }
}