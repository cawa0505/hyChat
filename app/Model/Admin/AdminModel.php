<?php


namespace App\Model\Admin;


use App\Model\BaseModel;

/**
 * TODO 管理员
 * Class AdminModel
 * @package App\Model\Admin
 */
class AdminModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = "admin";

    /**
     * @param $username
     * @return array
     */
    public function getUserByUserName($username)
    {
        $result = $this->newQuery()->where('username', $username)->first();
        if($result){
            return $result->toArray();
        }
        return [];
    }
}