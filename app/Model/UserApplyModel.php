<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 13:38
 */

namespace App\Model;

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;

/**
 * Class UserApplyModel
 * @package App\Model
 */
class UserApplyModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'user_apply';

    /**
     * 通过主键获取申请信息
     * @param $applyId
     * @param array $columns
     * @return Builder|Model|object|null
     */
    public function getApplyById($applyId, $columns = ['*'])
    {
        return $this->newQuery()->where('id', $applyId)->first($columns);
    }

    /**
     * 通过用户id获取申请信息
     * @param $userId
     * @param array $columns
     * @return array|null
     */
    public function getApplyByUserId($userId, $columns = ['*'])
    {
        $result = $this->newQuery()->where("friend_id", $userId)->where('status', 0)->groupBy(['user_id'])->get($columns);
        if ($result) {
            return $result->toArray();
        }

        return [];
    }

    /**
     * 创建申请信息
     * @param $data
     * @return bool
     */
    public function createUserApply($data)
    {
        $result = $this->newQuery()->insert($data);
        return $result;
    }

    /**
     *
     * @param array $applyId
     * @param array $data
     * @return bool|int
     */
    public function updateUserApply($applyId, $data)
    {
        $result = $this->newQuery()->where('id', $applyId)->update($data);
        return $result;
    }

    /**
     * 删除好友
     * @param $whereOne
     * @param $whereTwo
     * @return int|mixed
     */
    public function deleteFriendApply($whereOne,$whereTwo)
    {
       return $this->newQuery()->where($whereOne)->orWhere($whereTwo)->delete();
    }

}