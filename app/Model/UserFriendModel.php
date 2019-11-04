<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Utils\Collection;

/**
 * Class UserFriendModel
 * @package App\Model
 */
class UserFriendModel extends BaseModel
{
    /**
     * 关闭自动更新时间
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'user_friend';

    /**
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
     * @param $friendId
     * @param $userId
     * @return bool
     */
    public function addFriend($friendId, $userId)
    {
        return $this->newQuery()->insert(['friend_id' => $friendId, 'user_id' => $userId]);
    }

    /**
     * @param $friendId
     * @param $userId
     * @return int
     */
    public function deleteFriend($friendId, $userId)
    {
        return $this->newQuery()->where('friend_id', $friendId)->where('user_id', $userId)->update(['status' => 1]);
    }
}