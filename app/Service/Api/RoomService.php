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
        $result = redis()->sAdd(sprintf("user_%d_room", $userId), $friendId);
        return $this->success([$result]);
    }

    /**
     * @param $request
     * @return array
     */
    public function getMessageRecord($request)
    {
        $friendId = $request['friendId'];
        return $this->success();
    }


    /**
     * 删除用户关联房间
     * @param $userId
     * @param $friendId
     * @return array
     */
    public function deleteRoom($userId, $friendId)
    {
        $result = redis()->sRem(sprintf("user_%d_room", $userId), $friendId);
        return $this->success([$result]);
    }
}