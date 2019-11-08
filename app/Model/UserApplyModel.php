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
     * @return array|null
     */
    public function getApplyByUserId($userId)
    {
        $result = $this->newQuery()->where("user_id", $userId)->get();
        if ($result) {
            return $result->toArray();
        }

        return null;
    }

    /**
     * 创建申请信息
     * @param $data
     * @return bool
     */
    public function create($data)
    {
        $result = $this->newQuery()->insert($data);
        mongoClient()->insert('user.apply', $data);
        return $result;
    }

}