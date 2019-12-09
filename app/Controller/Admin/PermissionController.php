<?php


namespace App\Controller\Admin;


use App\Controller\AbstractController;
use App\Request\Admin\PermissionRequest;
use App\Service\Admin\PermissionService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class PermissionController extends AbstractController
{
    /**
     * @Inject()
     * @var PermissionService
     */
    private $permissionServer;

    /**
     * @return ResponseInterface
     */
    public function list()
    {
        $request = $this->request->all();
        $result = $this->permissionServer->getPermissionList($request);
        return $this->successResponse($result);
    }

    /**
     * @param PermissionRequest $request
     * @return ResponseInterface
     */
    public function create(PermissionRequest $request)
    {
        $result = $this->permissionServer->createPermission($request->all());
        return $this->successResponse($result);
    }

    /**
     * @param PermissionRequest $request
     * @return ResponseInterface
     */
    public function update(PermissionRequest $request)
    {
        $result = $this->permissionServer->updatePermission($request->all());
        return $this->successResponse($result);
    }

    /**
     * @param PermissionRequest $request
     * @return ResponseInterface
     */
    public function delete(PermissionRequest $request)
    {
        $result = $this->permissionServer->deletePermission($request->all());
        return $this->successResponse($result);
    }
}