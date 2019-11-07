<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/11
 * Time: 12:24
 */

namespace App\Service;

/**
 * Class RoomService
 * @package App\Service
 */
class RoomService extends BaseService
{
    /**
     * 创建房间
     * @param $userId
     * @param $friendId
     * @return array
     */
    public function createRoom($userId, $friendId)
    {
        mongoTask()->insert('user.room', ['user_id' => $userId, 'friend_id' => $friendId]);
        $result = redis()->sAdd(sprintf("user_%d_room", $userId), $friendId);
        return $this->success([$result]);
    }

    /**
     * 删除用户关联房间
     * @param $userId
     * @param $friendId
     * @return array
     */
    public function deleteRoom($userId, $friendId)
    {
        mongoTask()->delete('user.room', ['user_id' => $userId, 'friend_id' => $friendId]);
        $result = redis()->sRem(sprintf("user_%d_room", $userId), $friendId);
        return $this->success([$result]);
    }
}