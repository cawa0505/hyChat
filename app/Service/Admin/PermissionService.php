<?php

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
    private $PermissionModel;

    /**
     * 角色列表
     * @param $request
     * @return array
     */
    public function getPermissionList($request)
    {
        $page = isset($request['page']) ? $request['page'] : 1;
        $limit = isset($request['limit']) ? $request['page'] : 10;
        $result = $this->PermissionModel->getPermissionList($page, $limit);
        return $this->success($result);
    }

    /**
     * 创建角色
     * @param $request
     * @return array
     */
    public function createPermission($request)
    {
        $PermissionResult = $this->PermissionModel->getPermissionByName($request['name']);
        if ($PermissionResult) {
            return $this->fail(AdminCode::ALREADY_EXISTS);
        }
        $saveData = [
            'Permission_name' => $request['Permission_name'],
            'Permission_desc' => $request['Permission_desc'],
            'status' => $request['status']
        ];
        $result = $this->PermissionModel->newQuery()->insert($saveData);
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
    public function updatePermission($request)
    {
        $PermissionResult = $this->PermissionModel->getPermissionByName($request['name']);
        if ($PermissionResult && $request['id'] != $PermissionResult['id']) {
            return $this->fail(AdminCode::ALREADY_EXISTS);
        }
        $saveData = [
            'Permission_name' => $request['Permission_name'],
            'Permission_desc' => $request['Permission_desc'],
            'status' => $request['status']
        ];
        $result = $this->PermissionModel->newQuery()->where("id", $request['id'])->update($saveData);
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
    public function deletePermission($request)
    {
        $PermissionResult = $this->PermissionModel->getPermissionById($request['id']);
        if (!$PermissionResult) {
            return $this->fail(AdminCode::DELETE_ERROR);
        }
        return $this->success($PermissionResult);
    }

}