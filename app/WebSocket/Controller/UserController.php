<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/11
 * Time: 17:43
 */

declare(strict_types=1);

namespace App\WebSocket\Controller;

use App\Constants\MessageCode;
use App\Model\UserFriendModel;

/**
 * 单人|私聊房间
 * Class Room
 * @package App\WebSocket\Controller
 */
class UserController extends BaseController
{
    /**
     * {"controller":"User","action":"send","content":{"userId":"10001","message":"123456"}}
     * @return bool
     */
    public function send()
    {
        $data = $this->getData();
        $userId = $data['userId'];
        /** @var  $userFriend UserFriendModel */
        $userFriend = container()->get(UserFriendModel::class);
        $result = $userFriend->getFriendIdByFriendId($userId);
        if (!$result) {
            $this->push(MessageCode::NO_OTHER_FRIEND, [], "你不是对方好友,无法发送信息");
            return false;
        }
        return $this->sendToUser($this->sendMessage(101, ['content' => $data['message']]), $userId, $this->getUid());
    }
}