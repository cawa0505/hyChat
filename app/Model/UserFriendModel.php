<?php

declare (strict_types=1);

namespace App\Model;


/**
 * Class UserFriendModel
 * @package App\Model
 */
class UserFriendModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'user_friend';

    /**
     * 通过用户id获取所有好友id
     * @param $userId
     * @return array|null
     */
    public function getFriendIdsByUserId($userId)
    {
        $result = $this->newQuery()->where('user_id', $userId)->pluck('friend_id');
        if ($result) {
            return $result->toArray();
        }
        return [];
    }

    /**
     * 添加好友
     * @param $friendId
     * @param $userId
     * @return bool
     */
    public function createFriend($friendId, $userId)
    {
        return $this->newQuery()->insert(['friend_id' => $friendId, 'user_id' => $userId]);
    }

    /**
     * 删除好友
     * @param $friendId
     * @param $userId
     * @return int
     */
    public function deleteFriend($friendId, $userId)
    {
        return $this->newQuery()->where('friend_id', $friendId)->where('user_id', $userId)->update(['status' => 1]);
    }
}