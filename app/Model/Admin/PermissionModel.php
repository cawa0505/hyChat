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

    public function getPermissionByRoleId($roleId)
    {
        $result = $this->newQuery()->where('id', $roleId)->first();
        if($result){
            return $result->toArray();
        }
        return [];
    }
}