<?php
declare(strict_types=1);

namespace App\Controller\Admin;


use App\Controller\AbstractController;
use App\Request\Admin\AdminRequest;
use App\Service\Admin\AdminService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class AuthController extends AbstractController
{
    /**
     * @Inject()
     * @var AdminService
     */
    private $adminService;

    /**
     * 管理员登录
     * @param AdminRequest $request
     * @return ResponseInterface
     */
    public function login(AdminRequest $request)
    {
        $response = $this->adminService->handleLogin($request->all());
        return $this->successResponse($response);
    }

    /**
     * 管理员退出
     * @return ResponseInterface
     */
    public function logout()
    {
        $result = redis()->hDel('adminToken', $this->getUserId());

        return $this->successResponse($this->success($result));
    }
}