<?php


namespace App\Service\Admin;

use App\Constants\AdminCode;
use App\Model\Admin\AdminModel;
use App\Model\Admin\AdminRoleModel;
use App\Model\Admin\PermissionModel;
use App\Service\BaseService;
use App\Utility\Token;
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
        $roleId = container()->get(AdminRoleModel::class)->getRoleIdsByAdminId($result['id']);
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
            'mobile' => $request['mobile']
        ];
        $result = $this->adminModel->newQuery()->insert($saveData);
        if (!$result) {
            return $this->fail(AdminCode::CREATE_ERROR);
        }
        return $this->success($result);
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
            'mobile' => $request['mobile']
        ];
        $result = $this->adminModel->newQuery()->where("id", $request['id'])->update($saveData);
        if (!$result) {
            return $this->fail(AdminCode::UPDATE_ERROR);
        }
        return $this->success($result);
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
        return $this->success($adminResult);
    }
}