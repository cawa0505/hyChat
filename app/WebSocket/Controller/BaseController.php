<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/8
 * Time: 9:50
 */

namespace App\WebSocket\Controller;


use App\Traits\Response;
use App\WebSocket\Service\UserService;
use Hyperf\Di\Annotation\Inject;
use Swoole\Server;
use Swoole\WebSocket\Frame;

class BaseController
{
    use Response;

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

    /**
     * 设配端 1 App 2 Web
     * @var int
     */
    protected $loginType;

    public function __construct(Server $server, Frame $frame, $params, $loginType)
    {
        $this->server = $server;
        $this->frame = $frame;
        $this->params = $params;
        $this->loginType = $loginType;
    }

    /**
     * {"controller":"Index","action":"index","content":"123456"}
     * {"controller":"Index","action":"index","content":{"userId":"1","message":"123456"}}
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
     * 向指定用户推送
     * @param int $userId
     * @param array|string $data
     * @return int
     */
    public function sendToUser($userId, $data): int
    {
        $count = 0;
        /** @var UserService $userService */
        $userService = container()->get(UserService::class);
        $fdInfo = $userService->getUserFd($userId);
        if (isOneArray($fdInfo)) {
            $pushData = [
                'fd' => $fdInfo['fd'],
                'data' => $data
            ];
            redis()->publish($fdInfo['ip'], json_encode($pushData));
            $count++;
            return $count;
        }
        foreach ($fdInfo as $info) {
            $pushData = [
                'fd' => $info['fd'],
                'data' => $data
            ];
            redis()->publish($info['ip'], json_encode($pushData));
            $count++;
        }
        return $count;
    }
}