<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 13:38
 */

namespace App\Model;

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
     * @param $data
     * @return bool
     */
    public function create($data)
    {
        return $this->newQuery()->insert($data);
    }
}