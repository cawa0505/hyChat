<?php


namespace App\Model\Admin;


use App\Model\BaseModel;

/**
 * TODO 权限
 * Class PermissionModel
 * @package App\Model\Admin
 */
class PermissionModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = "permission";

    /**
     * @param $page
     * @param $limit
     * @param array $columns
     * @return array
     */
    public function getPermissionList($page, $limit, $columns = ['*'])
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
    public function getPermissionById($id)
    {
        $result = $this->newQuery()->where('id', $id)->first();
        if ($result) {
            return $result->toArray();
        }
        return [];
    }

    /**
     * @param $name
     * @return array
     */
    public function getPermissionByName($name)
    {
        $result = $this->newQuery()->where('name', $name)->first();
        if ($result) {
            return $result->toArray();
        }
        return [];
    }
}