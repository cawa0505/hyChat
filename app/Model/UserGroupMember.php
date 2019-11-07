<?php

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
        return $this->newQuery()->where("group_id",$groupId)->delete();
    }
}