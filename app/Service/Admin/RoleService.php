<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Constants\AdminCode;
use App\Model\Admin\RoleModel;
use App\Model\Admin\RolePermissionModel;
use App\Service\BaseService;
use Hyperf\DbConnection\Db;
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
     * @Inject()
     * @var RolePermissionModel
     */
    private $rolePermissionModel;

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
        Db::beginTransaction();
        $roleId = $this->roleModel->newQuery()->insertGetId($saveData);
        if (!$roleId) {
            Db::rollBack();
            return $this->fail(AdminCode::CREATE_ERROR);
        }
        if (isset($request['permission_ids'])) {
            foreach ($request['permission_ids'] as $item) {
                $rolePermissionData['role_id'] = $roleId;
                $rolePermissionData['permission_id'] = $item;
                $rolePermissionResult = $this->rolePermissionModel->newQuery()->where('role_id', $roleId)->where('permission_id', $item)->first();
                if ($rolePermissionResult) {
                    continue;
                }
                $rolePermission = $this->rolePermissionModel->newQuery()->insert($rolePermissionData);
                if (!$rolePermission) {
                    Db::rollBack();
                    return $this->fail(AdminCode::CREATE_ERROR);
                }
            }
        }
        //提交事务
        Db::commit();
        return $this->success($saveData);
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
        Db::beginTransaction();
        $result = $this->roleModel->newQuery()->where("id", $request['id'])->update($saveData);
        if (!$result) {
            Db::rollBack();
            return $this->fail(AdminCode::UPDATE_ERROR);
        }
        //通过角色id删除角色权限关系表中所有数据
        $rolePermissionResult = $this->roleModel->newQuery()->where('role_id', $request['role_id'])->delete();
        if (!$rolePermissionResult) {
            Db::rollBack();
            return $this->fail(AdminCode::DELETE_ERROR);
        }
        if (isset($request['permission_ids'])) {
            foreach ($request['permission_ids'] as $item) {
                $rolePermissionData['role_id'] = $request['role_id'];
                $rolePermissionData['permission_id'] = $item;
                $rolePermission = $this->rolePermissionModel->newQuery()->insert($rolePermissionData);
                if (!$rolePermission) {
                    Db::rollBack();
                    return $this->fail(AdminCode::CREATE_ERROR);
                }
            }
        }
        Db::commit();
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
        if (is_array($request['id'])) {
            $result = $this->roleModel->newQuery()->whereIn('role_id', $request['id'])->delete();
        } else {
            $result = $this->roleModel->newQuery()->where('role_id', $request['id'])->delete();
        }
        if (!$result) {
            Db::rollBack();
            return $this->fail(AdminCode::DELETE_ERROR);
        }
        //通过角色id删除角色权限关系表中所有数据
        $rolePermissionResult = $this->rolePermissionModel->newQuery()->whereIn('role_id', $request['id'])->delete();
        if (!$rolePermissionResult) {
            Db::rollBack();
            return $this->fail(AdminCode::DELETE_ERROR);
        }
        Db::commit();
        return $this->success($roleResult);
    }

}