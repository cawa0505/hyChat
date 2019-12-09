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

    public function getAdminList($page = 1, $limit = 10)
    {
        $result = $this->newQuery()->forPage($page, $limit)->get();
        if ($result) {
            return $result->toArray();
        }
        return [];
    }

    /**
     * @param $username
     * @return array
     */
    public function getAdminByUserName($username)
    {
        $result = $this->newQuery()->where('username', $username)->first();
        if ($result) {
            return $result->toArray();
        }
        return [];
    }
}