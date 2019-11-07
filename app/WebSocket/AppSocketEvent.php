<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/9/30
 * Time: 10:34
 */

namespace App\WebSocket;

use App\Model\UserModel;
use App\Utility\Token;
use App\WebSocket\Service\CommonServer;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;
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
     * @Inject()
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $stdoutLogger)
    {
        $this->logger = $stdoutLogger;
    }

    /**
     * @param Server $server
     * @param Request $request
     */
    public function onOpen(Server $server, Request $request): void
    {
        $params = $request->get;
        $server->getClientInfo($request->fd);
        if (!isset($params['token']) || empty($params['token'])) {
            $this->logger->info('token为空');
        } else {
            $tokenData = $this->container->get(Token::class)->decode($params['token']);
            if ($tokenData['status'] == 0) {
                $server->push($request->fd, $tokenData['msg']);
            } else {
                $userInfo = (array)$tokenData['result']['data'];
                // 将fd和用户id绑定
                $login_type = $userInfo['login_type'];
                $server->bind($request->fd, $userInfo['id']);
                //设置userId关联的fd
                /** @var CommonServer $common */
                $common = $this->container->get(CommonServer::class);
                $fdInfo = [
                    'ip' => swoole_get_local_ip()['eth0'],
                    'port' => env("SOCKET_PORT", 9502),
                    'fd' => $request->fd
                ];
                $common->setUserFd($login_type, $userInfo['id'], json_encode($fdInfo));
                $server->push($request->fd, 'welcome to you');
            }
        }
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
        } else {
            $data = json_decode($data, true);
            if (!is_array($data)) {
                $server->push($frame->fd, "decode message error!");
                return;
            }
            $class = '\\App\\WebSocket\\Controller\\' . ucfirst($data['class']);
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
                $obj = new $class($server, $frame, $this->container);
                call_user_func_array([$obj, $action], $params);
            } catch (ReflectionException $exception) {
                $this->logger->error($exception->getMessage());
            }
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
            /** @var CommonServer $common */
            $common = $this->container->get(CommonServer::class);
            // 获取fd关联的uid
            $userId = $common->getFdUser($fd);
            if ($userId) {
                // 删除fd关联的userId
                $common->deleteUserFd($userId);
            }
            $common->sendToSome(sprintf("用户{$fd}离开聊天室"), $common->getConnectionList(), [$fd]);
        }
    }
}