<?php

declare(strict_types=1);

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
     * @param $page
     * @param $limit
     * @param array $columns
     * @return array
     */
    public function getAdminList($page, $limit, $columns = ['*'])
    {
        $result = $this->newQuery()->forPage($page, $limit)->get($columns);
        if ($result) {
            $data = [
                'count' => $this->newQuery()->count(),
                'data' => $result->toArray(),
            ];
            return $data;
        }
        return [];
    }

    /**
     * @param $id
     * @return array
     */
    public function getAdminById($id)
    {
        $result = $this->newQuery()->where('id', $id)->first();
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