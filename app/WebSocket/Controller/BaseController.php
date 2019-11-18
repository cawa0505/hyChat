<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/8
 * Time: 9:50
 */

namespace App\WebSocket\Controller;


use App\Constants\MessageCode;
use App\Traits\PushMessage;
use App\Traits\Response;
use App\WebSocket\Service\UserService;
use Swoole\Server;
use Swoole\WebSocket\Frame;

class BaseController
{
    use Response, PushMessage;

    /**
     * @var \Swoole\WebSocket\Server
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

    public function __construct(Server $server, Frame $frame, $params)
    {
        $this->server = $server;
        $this->frame = $frame;
        $this->params = $params;
    }

    /**
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

    public function getUid()
    {
        $fdInfo = $this->server->getClientInfo($this->frame->fd);
        return isset($fdInfo['uid']) ?? null;
    }

    /**
     * @param $code
     * @param array $data
     * @param string $message
     */
    public function push($code, $data = [], $message = "")
    {
        $this->server->push($this->frame->fd, json_encode($this->sendMessage($code, $data, $message)));
    }
}