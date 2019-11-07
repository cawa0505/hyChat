<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/8
 * Time: 15:55
 */

namespace App\WebSocket\Service;

use App\Traits\Response;
use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Container\ContainerInterface;
use Swoole\Server;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;

/**
 * Class Common
 * @package App\Utility\Socket
 */
class CommonServer
{
    use Response;
    /**
     * 容器
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var WebSocketServer
     */
    protected $server;

    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    /**
     * @var Frame
     */
    protected $frame;

    /**
     * Common constructor.
     * @param Server $server
     * @param Frame $frame
     * @param ContainerInterface $container
     */
    public function __construct(Server $server, Frame $frame, ContainerInterface $container)
    {
        $this->server = $server;
        $this->frame = $frame;
        $this->container = $container;
        $this->logger = $container->get(StdoutLoggerInterface::class);
    }

    /**
     * {"class":"Index","action":"index","content":"123456"}
     * {"class":"Index","action":"index","content":{"userId":"1","message":"123456"}}
     * 获取消息
     * @return mixed
     */
    protected function getData()
    {
        $data = json_decode($this->frame->data, true);
        return $data['content'];
    }

    /**
     * @return mixed
     */
    protected function getFd()
    {
        return $this->frame->fd;
    }

    /**
     * 向指定用户推送
     * @param string $fd 接收者 fd
     * @param string $data
     * @param int $opCode
     * @param bool $finish
     * @return bool
     */
    public function push(string $fd, string $data, int $opCode = 1, bool $finish = true): bool
    {
        if (!$this->server->exist($fd)) {
            return false;
        }
        $this->logger->debug("广播: 向用户 {$fd} 发送消息. 数据: {$data}");
        return $this->server->push($fd, $data, $opCode, $finish);
    }

    /**
     * 发送消息给指定的用户
     * @param int $receiver 接收者 fd
     * @param string $data
     * @param int $sender 发送者 fd
     * @return int
     */
    public function sendTo(int $receiver, string $data, int $sender = -1): int
    {
        $fromUser = $sender < 0 ? 'SYSTEM' : $sender;
        if (!$this->server->exist($receiver)) {
            return false;
        }
        $this->logger->debug("广播: {$fromUser} 向用户{ $receiver} 发送消息. 数据: {$data}");

        return $this->server->push($receiver, $data, 1, true) ? 1 : 0;
    }

    /**
     * 发送消息给在线所有用户
     * @param string $data
     * @param int $sender
     * @param int $pageSize
     * @return int
     */
    public function sendToAll(string $data, int $sender = 0, int $pageSize = 50): int
    {
        $startFd = 0;
        $count = 0;
        $fromUser = $sender < 1 ? 'SYSTEM' : $sender;
        $this->logger->debug("广播: {$fromUser} 向所有用户发送消息. 消息: {$data}");
        while (true) {
            $fdList = $this->server->connection_list($startFd, $pageSize);
            if ($fdList === false || ($num = count($fdList)) === 0) {
                break;
            }
            $count += $num;
            $startFd = end($fdList);
            foreach ($fdList as $fd) {
                $info = $this->getClientInfo($fd);
                if (isset($info['websocket_status']) && $info['websocket_status'] > 0) {
                    $this->server->push($fd, $data);
                }
            }
        }
        return $count;
    }

    /**
     * 发送消息指定用户
     * @param string $data
     * @param array $receivers
     * @param array $excluded
     * @param int $sender
     * @param int $pageSize
     * @return int
     */
    public function sendToSome(string $data, array $receivers = [], array $excluded = [], int $sender = 0, int $pageSize = 50): int
    {
        $count = 0;
        $fromUser = $sender < 1 ? 'SYSTEM' : $sender;
        if ($receivers && !$excluded) {
            $this->logger->debug("广播: {$fromUser} 给某个指定用户发送消息. 数据: {$data}");
            foreach ($receivers as $receiver) {
                if ($this->exist($receiver)) {
                    $count++;
                    $this->server->push($receiver, $data);
                }
            }
            return $count;
        }
        $startFd = 0;
        $excluded = $excluded ? (array)array_flip($excluded) : [];
        $this->logger->debug((string)"广播: {$fromUser} 把信息发给每个人，除了一些人. 数据: {$data}");
        while (true) {
            $fdList = $this->server->connection_list($startFd, $pageSize);
            if ($fdList === false || ($num = count($fdList)) === 0) {
                break;
            }
            $count += $num;
            $startFd = end($fdList);
            foreach ($fdList as $fd) {
                if (isset($excluded[$fd])) {
                    continue;
                }
                $this->server->push($fd, $data);
            }
        }
        return $count;
    }

    /**
     * 判断是否在线
     * @param int $fd
     * @return bool
     */
    public function exist(int $fd): bool
    {
        return $this->server->exist($fd);
    }

    /**
     * 获取fd详情
     * @param int $fd
     * @return array
     */
    public function getClientInfo(int $fd): array
    {
        return $this->server->getClientInfo($fd);
    }

    /**
     * 获取用户id
     * @param $fd
     * @return int
     */
    public function getFdUser($fd)
    {
        $result = $this->server->getClientInfo($fd);
        return $result['uid'] ?? 0;
    }

    /**
     * 获取所有在线用户
     * @return array
     */
    public function getConnectionList(): array
    {
        $result = $this->server->getClientList(0);
        if (!$result) {
            return [];
        }
        return $result;
    }

    /**
     * 给用户发信息
     * @param $userId
     * @param $message
     * @return bool
     */
    public function sendToUser($userId, $message)
    {
        $fd = $this->getUserFd($userId);
        return $this->push($fd, $message);
    }

    /**
     * 给指定用户发送信息
     * @param $userIds
     * @param $message
     * @param array $excludeUserId
     * @return int
     */
    public function sendToSomeUser($userIds, $message, $excludeUserId = [])
    {
        $receivers = [];
        foreach ($userIds as $userId) {
            $receivers[] = $this->getUserFd($userId);
        }
        $exclude = [];
        if ($excludeUserId) {
            foreach ($excludeUserId as $userId) {
                $exclude[] = $this->getUserFd($userId);
            }
        }
        return $this->sendToSome($message, $receivers, $exclude);
    }

    /**
     * 发送信息给群组
     * @param $group
     * @param $data
     */
    public function sendToGroup($group, $data)
    {
    }

    /**
     * 设置user关联的fd
     * @param $login_type int 登陆端
     * @param $userId int 用户id
     * @param $fd int
     * @return bool|int
     */
    public function setUserFd($login_type,$userId, $fd)
    {
        return redis()->hSet('userFd', (string)$userId, $fd);
    }

    /**
     * 获取user的关联的fd
     * @param $userId
     * @return mixed
     */
    public function getUserFd($userId)
    {
        return redis()->hGet('userFd', (string)$userId);
    }

    /**
     * 删除user关联的fd
     * @param $userId
     * @return mixed
     */
    public function deleteUserFd($userId)
    {
        return redis()->hDel('userFd', (string)$userId);
    }
}