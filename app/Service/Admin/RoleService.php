<?php


namespace App\Service\Admin;


use App\Constants\AdminCode;
use App\Model\Admin\RoleModel;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;

/**
 * Class RoleService
 * @package App\Service\Admin
 */
class RoleService extends BaseService
{
    /**
     * @Inject()
     * @var RoleModel
     */
    private $roleModel;

    /**
     * 角色列表
     * @param $request
     * @return array
     */
    public function getRoleList($request)
    {
        $page = isset($request['page']) ? $request['page'] : 1;
        $limit = isset($request['limit']) ? $request['page'] : 10;
        $result = $this->roleModel->getRoleList($page, $limit);
        return $this->success($result);
    }

    /**
     * 创建角色
     * @param $request
     * @return array
     */
    public function createRole($request)
    {
        $roleResult = $this->roleModel->getRoleByRoleName($request['role_name']);
        if ($roleResult) {
            return $this->fail(AdminCode::ALREADY_EXISTS);
        }
        $saveData = [
            'role_name' => $request['role_name'],
            'role_desc' => $request['role_desc'],
            'status' => $request['status']
        ];
        $result = $this->roleModel->newQuery()->insert($saveData);
        if (!$result) {
            return $this->fail(AdminCode::CREATE_ERROR);
        }
        return $this->success($result);
    }

    /**
     * 更新角色信息
     * @param $request
     * @return array
     */
    public function updateRole($request)
    {
        $roleResult = $this->roleModel->getRoleByRoleName($request['role_name']);
        if ($roleResult && $request['id'] != $roleResult['id']) {
            return $this->fail(AdminCode::ALREADY_EXISTS);
        }
        $saveData = [
            'role_name' => $request['role_name'],
            'role_desc' => $request['role_desc'],
            'status' => $request['status']
        ];
        $result = $this->roleModel->newQuery()->where("id", $request['id'])->update($saveData);
        if (!$result) {
            return $this->fail(AdminCode::UPDATE_ERROR);
        }
        return $this->success($result);
    }

    /**
     * 删除角色
     * @param $request
     * @return array
     */
    public function deleteRole($request)
    {
        $roleResult = $this->roleModel->getRoleById($request['id']);
        if (!$roleResult) {
            return $this->fail(AdminCode::DELETE_ERROR);
        }
        return $this->success($roleResult);
    }

}