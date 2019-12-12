<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Constants\AdminCode;
use App\Model\Admin\PermissionModel;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;

/**
 * Class PermissionService
 * @package App\Service\Admin
 */
class PermissionService extends BaseService
{
    /**
     * @Inject()
     * @var PermissionModel
     */
    private $permissionModel;

    /**
     * 权限列表
     * @param $request
     * @return array
     */
    public function getPermissionList($request)
    {
        $page = isset($request['page']) ? $request['page'] : 1;
        $limit = isset($request['limit']) ? $request['page'] : 10;
        $result = $this->permissionModel->getPermissionList($page, $limit);
        return $this->success($result);
    }

    /**
     * 创建权限
     * @param $request
     * @return array
     */
    public function createPermission($request)
    {
        $permissionResult = $this->permissionModel->getPermissionByName($request['name']);
        if ($permissionResult) {
            return $this->fail(AdminCode::ALREADY_EXISTS);
        }
        $saveData = [
            'name' => $request['name'],
            'url' => $request['url'],
            'parent_id' => $request['parent_id']
        ];
        $result = $this->permissionModel->newQuery()->insert($saveData);
        if (!$result) {
            return $this->fail(AdminCode::CREATE_ERROR);
        }
        return $this->success($saveData);
    }

    /**
     * 更新权限信息
     * @param $request
     * @return array
     */
    public function updatePermission($request)
    {
        $permissionResult = $this->permissionModel->getPermissionByName($request['name']);
        if ($permissionResult && $request['id'] != $permissionResult['id']) {
            return $this->fail(AdminCode::ALREADY_EXISTS);
        }
        $saveData = [
            'id' => $request['id'],
            'name' => $request['name'],
            'url' => $request['url'],
            'parent_id' => $request['parent_id']
        ];
        $result = $this->permissionModel->newQuery()->where("id", $request['id'])->update($saveData);
        if (!$result) {
            return $this->fail(AdminCode::UPDATE_ERROR);
        }
        return $this->success($permissionResult);
    }

    /**
     * 删除权限
     * @param $request
     * @return array
     */
    public function deletePermission($request)
    {
        $permissionResult = $this->permissionModel->getPermissionById($request['id']);
        if (!$permissionResult) {
            return $this->fail(AdminCode::DELETE_ERROR);
        }
        if (is_array($request['id'])) {
            $result = $this->permissionModel->newQuery()->whereIn('id', $request['id'])->delete();
        } else {
            $result = $this->permissionModel->newQuery()->where('id', $request['id'])->delete();
        }
        if (!$result) {
            return $this->fail(AdminCode::DELETE_ERROR);
        }
        return $this->success($permissionResult);
    }

}