<?php

declare(strict_types=1);

namespace App\Model\Admin;


use App\Model\BaseModel;

/**
 * TODO 管理员角色关系
 * Class AdminRoleModel
 * @package App\Model\Admin
 */
class AdminRoleModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = "admin_role";

    public function getRoleIdsByAdminId($adminId)
    {
        $result = $this->newQuery()->where('admin_id', $adminId)->first();
        if ($result) {
            return $result->toArray();
        }
        return [];
    }
}