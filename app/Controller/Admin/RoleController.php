<?php


namespace App\Controller\Admin;


use App\Controller\AbstractController;
use App\Request\Admin\RoleRequest;
use App\Service\Admin\RoleService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class RoleController extends AbstractController
{
    /**
     * @Inject()
     * @var RoleService
     */
    private $roleServer;

    /**
     * @return ResponseInterface
     */
    public function list()
    {
        $request = $this->request->all();
        $result = $this->roleServer->getRoleList($request);
        return $this->successResponse($result);
    }

    /**
     * @param RoleRequest $request
     * @return ResponseInterface
     */
    public function create(RoleRequest $request)
    {
        $result = $this->roleServer->createRole($request->all());
        return $this->successResponse($result);
    }

    /**
     * @param RoleRequest $request
     * @return ResponseInterface
     */
    public function update(RoleRequest $request)
    {
        $result = $this->roleServer->updateRole($request->all());
        return $this->successResponse($result);
    }

    /**
     * @param RoleRequest $request
     * @return ResponseInterface
     */
    public function delete(RoleRequest $request)
    {
        $result = $this->roleServer->deleteRole($request->all());
        return $this->successResponse($result);
    }
}