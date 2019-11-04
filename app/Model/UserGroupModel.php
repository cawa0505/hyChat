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

    public function getGroupByUserId($userId, $columns = ['*'])
    {
        $group = $this->newQuery()->where('user_id', $userId)->get($columns);
        if ($group) {
            return $group->toArray();
        }
        return null;
    }
}