<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 13:34
 */

namespace App\Model;


/**
 * 用户群组
 * Class UserGroupModel
 * @package App\Model
 */
class UserGroupModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'user_group';

    /**
     * 通过ID获取单条数据
     * @param $id
     * @return array|null
     */
    public function getFind($id)
    {
        return $this->newQuery()->find($id)->toArray();
    }
    /**
     * 通过用户id获取群组
     * @param $userId
     * @param array $columns
     * @return array|null
     */
    public function getGroupByUserId($userId, $columns = ['*'])
    {
        $group = $this->newQuery()->where('user_id', $userId)->get($columns);
        if ($group) {
            return $group->toArray();
        }
        return null;
    }

    public function createGroup($data)
    {
        return $this->newQuery()->insert($data);
    }

    public function updateGroupInfo($param,$userId)
    {
        return $this->newQuery()->where("user_id",$userId)->update($param);
    }
}