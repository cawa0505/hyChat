<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/11
 * Time: 12:24
 */

namespace App\Service;


use Hyperf\DbConnection\Db;

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
        $result = redis()->sAdd(sprintf("user_%d_room", $userId), $friendId);
        Db::table("user_room")->insert(['user_id' => $userId, 'friend_id' => $friendId]);
        return $this->success([$result]);
    }

    /**
     * @param $userId
     * @param $friendId
     * @return array
     */
    public function deleteRoom($userId, $friendId)
    {
        $result = redis()->sRem(sprintf("user_%d_room", $userId), $friendId);
        Db::table("user_room")->where(['user_id' => $userId, 'friend_id' => $friendId])->delete();
        return $this->success([$result]);
    }
}