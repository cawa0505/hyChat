<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/8
 * Time: 9:50
 */

namespace App\WebSocket\Controller;


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
     * {"controller":"Index","action":"index","content":"123456"}
     * {"controller":"Room","action":"index","content":{"userId":"1","message":"123456"}}
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
}