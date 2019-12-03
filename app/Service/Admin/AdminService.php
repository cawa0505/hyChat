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
        $result = $this->adminModel->getUserByUserName($request['username']);
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
}