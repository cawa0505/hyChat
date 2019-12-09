<?php

declare(strict_types=1);

namespace App\Model;


/**
 * Class UserGroupMember
 * @package App\Model
 */
class UserGroupMember extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'user_group_member';

    /**
     * 获取群组成员
     * @param $groupId
     * @return array
     */
    public function getGroupMember($groupId)
    {

        return $this->newQuery()->where("group_id", $groupId)->get()->toArray();

    }

    /**
     * 插入群成员
     * @param $data
     * @return bool
     */
    public function createData($data)
    {
        return $this->newQuery()->insert($data);
    }

    /**
     * 解散成员
     * @param $groupId
     * @return int|mixed
     */
    public function deleteMember($groupId)
    {
        return $this->newQuery()->where("group_id", $groupId)->delete();
    }

    /**
     * 更改成员群昵称
     * @param $data
     * @param $where
     * @return int
     */
    public function updateMemberNick($data, $where)
    {
        return $this->newQuery()->where($where)->update($data);
    }


}