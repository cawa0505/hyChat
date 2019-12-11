<?php

declare(strict_types=1);

namespace App\Model\Admin;


use App\Model\BaseModel;

/**
 * TODO 角色
 * Class RoleModel
 * @package App\Model\Admin
 */
class RoleModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = "role";

    /**
     * @param $page
     * @param $limit
     * @param array $columns
     * @return array
     */
    public function getRoleList($page, $limit, $columns = ['*'])
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
    public function getRoleById($id)
    {
        $result = $this->newQuery()->where('id', $id)->first();
        if ($result) {
            return $result->toArray();
        }
        return [];
    }

    /**
     * @param $roleName
     * @return array
     */
    public function getRoleByRoleName($roleName)
    {
        $result = $this->newQuery()->where('role_name', $roleName)->first();
        if ($result) {
            return $result->toArray();
        }
        return [];
    }
}