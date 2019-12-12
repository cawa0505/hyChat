<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Constants\AdminCode;
use App\Model\Admin\AdminModel;
use App\Model\Admin\AdminRoleModel;
use App\Model\Admin\PermissionModel;
use App\Service\BaseService;
use App\Utility\Token;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

/**
 * Class AdminService
 * @package App\Service\Admin
 */
class AdminService extends BaseService
{
    /**
     * @Inject()
     * @var AdminModel
     */
    private $adminModel;

    /**
     * @Inject()
     * @var AdminRoleModel
     */
    private $adminRoleModel;

    /**
     * 管理员登录
     * @param $request
     * @return array
     */
    public function handleLogin($request)
    {
        $result = $this->adminModel->getAdminByUserName($request['username']);
        if (!$result) {
            return $this->fail(AdminCode::USER_NOT_FOUND);
        }
        if (validatePasswordHash($request['password'], $result['password'])) {
            return $this->fail(AdminCode::USER_PASSWORD_ERROR);
        }
        if ($result['status'] == 1) {
            return $this->fail(AdminCode::USER_DISABLE);
        }
        // 通过id查询角色id
        $roleId = $this->adminRoleModel->getRoleIdsByAdminId($result['id']);
        // 通过角色id获取权限
        $permissions = container()->get(PermissionModel::class)->getPermissionByRoleId($roleId);
        $permissionUrl = array_column($permissions, 'url');
        redis()->set('admin_' . $result['id'] . '_permission', json_encode($permissionUrl), 60 * 60);
        $token = container()->get(Token::class)->encode($result);
        redis()->set('admin_token_' . $result['id'], $token, 60 * 60);
        return $this->success($token);
    }

    /**
     * @param $request
     * @return array
     */
    public function getAdminList($request)
    {
        $page = isset($request['page']) ? $request['page'] : 1;
        $limit = isset($request['limit']) ? $request['page'] : 10;
        $result = $this->adminModel->getAdminList($page, $limit, ['id', 'username', 'mobile', 'status', 'last_login_ip', 'last_login_time', 'create_time', 'update_time']);
        return $this->success($result);
    }

    /**
     * @param $request
     * @return array
     */
    public function createAdmin($request)
    {
        $adminResult = $this->adminModel->getAdminByUserName($request['username']);
        if ($adminResult) {
            return $this->fail(AdminCode::ALREADY_EXISTS);
        }
        $saveData = [
            'username' => $request['username'],
            'password' => makePasswordHash($request['password']),
            'mobile' => $request['mobile'],
            'status' => $request['status'],
            'create_time' => time(),
        ];
        Db::beginTransaction();
        // 添加管理员信息
        $adminId = $this->adminModel->newQuery()->insertGetId($saveData);
        if (!$adminId) {
            Db::rollBack();
            return $this->fail(AdminCode::CREATE_ERROR);
        }
        // 处理角色Ids
        if (isset($request['role_ids'])) {
            foreach ($request['role_ids'] as $role_id) {
                $adminRoleData['admin_id'] = $adminId;
                $adminRoleData['role_id'] = $role_id;
                $adminRoleResult = $this->adminRoleModel->newQuery()->where('admin_id', $adminId)->where('role_id', $role_id)->first();
                if ($adminRoleResult) {
                    continue;
                }
                // 将管理员id和角色id存入关系表
                $rolePermission = $this->adminRoleModel->newQuery()->insert($adminRoleData);
                if (!$rolePermission) {
                    Db::rollBack();
                    return $this->fail(AdminCode::CREATE_ERROR);
                }
            }
        }
        Db::commit();
        return $this->success($saveData);
    }

    /**
     * @param $request
     * @return array
     */
    public function updateAdmin($request)
    {
        $adminResult = $this->adminModel->getAdminByUserName($request['username']);
        if ($adminResult && $request['id'] != $adminResult['id']) {
            return $this->fail(AdminCode::ALREADY_EXISTS);
        }
        $saveData = [
            'username' => $request['username'],
            'password' => makePasswordHash($request['password']),
            'mobile' => $request['mobile'],
            'status' => $request['status'],
            'update_time' => time(),
        ];
        Db::beginTransaction();
        // 更新管理员信息
        $result = $this->adminModel->newQuery()->where("id", $request['id'])->update($saveData);
        if (!$result) {
            return $this->fail(AdminCode::UPDATE_ERROR);
        }
        // 删除用户关联角色
        $adminRoleResult = $this->adminRoleModel->newQuery()->where('admin_id', $request['admin_id'])->delete();
        if (!$adminRoleResult) {
            Db::rollBack();
            return $this->fail(AdminCode::DELETE_ERROR);
        }
        // 处理角色Ids
        if (isset($request['role_ids'])) {
            foreach ($request['role_ids'] as $role_id) {
                $adminRoleData['admin_id'] = $request['admin_id'];
                $adminRoleData['role_id'] = $role_id;
                $adminRole = $this->adminRoleModel->newQuery()->insert($adminRoleData);
                if (!$adminRole) {
                    Db::rollBack();
                    return $this->fail(AdminCode::CREATE_ERROR);
                }
            }
        }
        Db::commit();
        return $this->success($adminResult);
    }

    /**
     * @param $request
     * @return array
     */
    public function deleteAdmin($request)
    {
        $adminResult = $this->adminModel->getAdminById($request['id']);
        if (!$adminResult) {
            return $this->fail(AdminCode::DELETE_ERROR);
        }
        if (is_array($request['id'])) {
            $result = $this->adminModel->newQuery()->whereIn('admin_id', $request['id'])->delete();
        } else {
            $result = $this->adminModel->newQuery()->where('admin_id', $request['id'])->delete();
        }
        if (!$result) {
            Db::rollBack();
            return $this->fail(AdminCode::DELETE_ERROR);
        }
        // 删除管理员和角色关系
        $adminRoleResult = $this->adminRoleModel->newQuery()->whereIn('admin_id', $request['id'])->delete();
        if (!$adminRoleResult) {
            Db::rollBack();
            return $this->fail(AdminCode::DELETE_ERROR);
        }
        Db::commit();
        return $this->success($adminResult);
    }
}