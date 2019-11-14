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
     * @param array $column
     * @return array
     */
    public function getFriendIdsByUserId($userId, $column = ["*"])
    {
        $result = $this->newQuery()->where('user_id', $userId)->get($column);
        if ($result) {
            return $result->toArray();
        }
        return [];
    }

    /**
     * 通过好友id获取信息
     * @param $friendId
     * @param array $column
     * @return array
     */
    public function getFriendIdByFriendId($friendId, $column = ["*"])
    {
        $result = $this->newQuery()->where('friend_id', $friendId)->first($column);
        if ($result) {
            return $result->toArray();
        }

        return [];
    }

    /**
     * @param $data
     * @return bool
     */
    public function createFriend($data)
    {
        return $this->newQuery()->insert($data);
    }

    /**
     * 通过好友id膝盖信息
     * @param $friendId
     * @param $data
     * @return int
     */
    public function updateFriendName($friendId, $data)
    {
        return $this->newQuery()->where('friend_id', $friendId)->update($data);
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