<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/11
 * Time: 12:24
 */

namespace App\Service\Api;

use App\Service\BaseService;
use MongoDB\Driver\Exception\Exception;

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
        mongoClient()->insert('user.room', ['user_id' => $userId, 'friend_id' => $friendId]);
        $result = redis()->sAdd(sprintf("user_%d_room", $userId), $friendId);
        return $this->success([$result]);
    }

    /**
     * @param $request
     * @return array
     * @throws Exception
     */
    public function getMessageRecord($request)
    {
        $friendId = $request['friendId'];
        $limit = 20;
        $page = isset($request['page']) ? $request['page'] : 1;
        $skip = ($page - 1) * $limit;
        $options = [
            'projection' => ['_id' => 0],
            'sort' => ['create_time' => -1],
            'skip' => $skip,
            'limit' => $limit
        ];
        $result = mongoClient()->query('group.message', ['friend' => $friendId], $options);
        return $this->success($result);
    }


    /**
     * 删除用户关联房间
     * @param $userId
     * @param $friendId
     * @return array
     */
    public function deleteRoom($userId, $friendId)
    {
        mongoClient()->delete('user.room', ['user_id' => $userId, 'friend_id' => $friendId]);
        $result = redis()->sRem(sprintf("user_%d_room", $userId), $friendId);
        return $this->success([$result]);
    }
}