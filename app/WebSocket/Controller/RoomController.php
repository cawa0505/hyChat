<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/11
 * Time: 17:43
 */

namespace App\WebSocket\Controller;

use App\Constants\MessageCode;
use App\Model\UserFriendModel;

/**
 * 单人|私聊房间
 * Class Room
 * @package App\WebSocket\Controller
 */
class RoomController extends BaseController
{
    /**
     * {"controller":"Room","action":"send","content":{"userId":"1001","message":"123456"}}
     * @return bool
     */
    public function send()
    {
        $data = $this->getData();
        $userId = $data['userId'];
        $message = $data['message'];
        /** @var  $userFriend UserFriendModel */
        $userFriend = container()->get(UserFriendModel::class);
        $result = $userFriend->getFriendIdByFriendId($userId);
        if (!$result) {
            $this->push(MessageCode::NO_OTHER_FRIEND, [], "你不是对方好友,无法发送信息");
            return false;
        }
        $this->sendToUser($userId, $message);
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
        return true;
    }
}