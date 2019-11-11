<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/11
 * Time: 17:43
 */

namespace App\WebSocket\Controller;

use App\Service\RoomService;
use Hyperf\Di\Annotation\Inject;

/**
 * 单人|私聊房间
 * Class Room
 * @package App\WebSocket\Controller
 */
class RoomController extends BaseController
{
    /**
     * {"controller":"Room","action":"send","content":{"userId":"1","message":"123456"}}
     */
    public function send()
    {
        $data = $this->getData();
        $this->sendToUser($data['userId'], $data['message']);
        $senderId = $this->getUid();
        go(function () use ($senderId, $data) {
            mongoClient()->insert('room.message',
                [
                    'sender' => $senderId,
                    'receiver' => $data['userId'],
                    'message' => $data['message'],
                    'create_time' => time()
                ]
            );
        });
    }
}