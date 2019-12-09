<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 13:34
 */
declare(strict_types=1);

namespace App\Model;


use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\Utils\Collection;

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
     * 群成员
     * @return HasMany
     */
    public function GroupMember()
    {
        return $this->hasMany(UserGroupMember::class, 'group_id', 'id');
    }

    /**
     * todo 通过用户id获取群组
     * @param $userId
     * @param array $columns
     * @return array|null
     */
    public function getGroupByUserId($userId, $columns = ['*'])
    {
        $group = $this->newQuery()->where('user_id', $userId)->get($columns);

        foreach ($group as &$item) {
            $item->memberList = $item->GroupMember()->pluck("user_id");
        };
        if ($group) {
            return $group->toArray();
        }
        return null;
    }

    /**
     * TODO 创建群组
     * @param $data
     * @return int
     */
    public function createGroup($data)
    {
        return $this->newQuery()->insertGetId($data);
    }

    /**
     * todo 编辑群组信息
     * @param $param
     * @param $userId
     * @return int
     */
    public function updateGroupInfo($param, $userId)
    {
        return $this->newQuery()->where("user_id", $userId)->update($param);
    }

    /**
     * 删除群组
     * @param $id
     * 群组ID
     * @return int|mixed
     */
    public function deleteGroup($id)
    {
        return $this->newQuery()->where("id", $id)->delete();
    }
}