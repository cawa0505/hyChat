<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/8
 * Time: 15:55
 */

namespace App\WebSocket;


use App\Traits\Response;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;
use Swoole\WebSocket\Server;

class Common
{
    use Response;

    /**
     * @var WebSocketServer
     */
    protected $server;

    /**
     * @var Frame
     */
    protected $frame;

    /**
     * @var array
     */
    protected $params;

    /**
     * 设配端 1 App 2 Web
     * @var int
     */
    protected $loginType;

    /**
     * Common constructor.
     * @param Server $server
     * @param Frame $frame
     * @param array $params
     * @param int $loginType
     */
    public function __construct(Server $server, Frame $frame, array $params, int $loginType)
    {
        $this->server = $server;
        $this->frame = $frame;
        $this->params = $params;
        $this->loginType = $loginType;
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
    public function getFd()
    {
        return $this->frame->fd;
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
        return isset($result['uid']) ?? 0;
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
     * 向指定用户推送
     * @param array $fdInfo 接收者 fd
     * @param string $data
     * @return bool
     */
    public function push($fdInfo, string $data): bool
    {
        // 判断发送的fd是否为本机ip
        if ($fdInfo['ip'] == getLocalIp()) {
            if ($this->server->isEstablished($fdInfo['fd'])) {
                $this->server->push($fdInfo['fd'], $data);
                return true;
            }
            return false;
        }
        $host = sprintf("%s:%d/socket?token=system", $fdInfo["ip"], $fdInfo['port']);
        $client = socketClient($host);
        return $client->push($data);
    }


    // TODO -------------------绑定用户-------------------------------

    /**
     * 设置user关联的fd
     * @param $userId int
     * @param $fd int
     * @param $login_type int 设配端 1 App 2 Web
     * @return bool|int
     */
    public function setUserFd($userId, $fd, $login_type = 0)
    {
        return redis()->hSet('userFd_' . $login_type, (string)$userId, $fd);
    }

    /**
     * 获取user的关联的fd
     * @param $userId int
     * @param $login_type int 设配端 1 App 2 Web
     * @return array|mixed|string
     */
    public function getUserFd($userId, $login_type = 0)
    {
        if (!$login_type) {
            // 获取app端绑定的userId绑定的fd信息
            $userFdApp = redis()->hGet('userFd_' . 1, (string)$userId);
            // 获取web端绑定的userId绑定的fd信息
            $userFdWeb = redis()->hGet('userFd_' . 2, (string)$userId);
            if (!$userFdApp) {
                return json_decode($userFdWeb, true);
            }
            if ($userFdWeb) {
                return json_decode($userFdApp, true);
            }
            return array_merge(json_decode($userFdWeb, true), json_decode($userFdApp, true));
        }
        $fdInfo = redis()->hGet('userFd_' . $login_type, (string)$userId);
        return json_decode($fdInfo, true);
    }

    /**
     * @param $userId int
     * @param $login_type int
     * @return bool|int
     */
    public function deleteUserFd($userId, $login_type = 0)
    {
        return redis()->hDel('userFd_' . $login_type, (string)$userId);
    }
}