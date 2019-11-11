<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 13:39
 */

namespace App\Service;

use App\Constants\ApiCode;
use App\Model\UserFriendModel;
use App\Model\UserModel;
use App\Utility\GenNameFirstLetter;
use Hyperf\Di\Annotation\Inject;

/**
 * Class FriendService
 * @package App\Service
 */
class FriendService extends BaseService
{
    /**
     * @Inject()
     * @var UserModel
     */
    private $userModel;

    /**
     * @Inject()
     * @var UserFriendModel
     */
    private $userFriendModel;

    /**
     * @param $userId
     * @return array
     */
    public function getUserFriend($userId)
    {
        $friend = $this->userFriendModel->getFriendIdsByUserId($userId);
        $result = [];
        if ($friend) {
            foreach ($friend as $item) {
                $first_letter = GenNameFirstLetter::instance()->getFirstChar($item['friend_name']);
                $result[$first_letter][] = $item;
            }
        }
        return $this->success($result);
    }


    /**
     * @param $account
     * @return array
     */
    public function searchFriend($account)
    {
        $result = $this->userModel->searchUserByAccount($account);
        return $this->success($result);
    }

    /**
     * 删除好友
     * @param $friendId
     * @param $userId
     * @return array
     */
    public function deleteFriend($friendId, $userId)
    {
        $result = $this->userFriendModel->deleteFriend($friendId, $userId);
        return $this->success($result);
    }
}