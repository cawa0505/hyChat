<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/9/30
 * Time: 10:34
 */

namespace App\WebSocket\Event;

use App\Model\UserModel;
use App\Utility\Token;
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
 * Class PcSocketEvent
 * @package App\WebSocket
 */
class PcSocketEvent implements OnOpenInterface, OnMessageInterface, OnCloseInterface
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
        dd($request->fd);
        $server->bind($request->fd, $params['id']);
        $server->push($request->fd,'hello');
        dd($server->getClientInfo($request->fd));
//        $params = $request->get;
//        $this->logger->info(json_encode($params));
//        if (!isset($params['token']) || empty($params['token'])) {
//            $this->logger->info('token为空');
//        } else {
//            $tokenData = $this->container->get(Token::class)->decode($params['token']);
//            if ($tokenData['status'] == 0) {
//                $server->push($request->fd, $tokenData['msg']);
//            } else {
//                $userInfo = (array)$tokenData['result']['data'];
//                $user = $this->container->get(UserModel::class)->getUserByUserId($userInfo['id']);
//                // 将fd和用户id绑定
//                $server->bind($request->fd, $user['id']);
//                //设置userId关联的fd
//                /** @var Common $common */
//                $common = $this->container->get(Common::class);
//                $fdInfo = [
//                    'ip' => swoole_get_local_ip()['eth0'],
//                    'port' => 9501,
//                    'fd' => $request->fd
//                ];
//                $common->setUserFd($user['id'], json_encode($fdInfo));
//                $server->push($request->fd, 'welcome to you');
//                $common->sendToSome(sprintf("用户{$request->fd}加入聊天室"), $common->getConnectionList(), [$request->fd]);
//            }
//        }
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
            /** @var Common $common */
            $common = $this->container->get(Common::class);
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