<?php


namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Request\Admin\AdminRequest;
use App\Service\Admin\AdminService;
use Hyperf\Di\Annotation\Inject;

/**
 * Class AdminController
 * @package App\Controller\Admin
 */
class AdminController extends AbstractController
{
    /**
     * @Inject()
     * @var AdminService
     */
    private $adminServer;

    /**
     * @return array
     */
    public function list()
    {
        $request = $this->request->all();
        $result = $this->adminServer->getAdminList($request);
        return $this->success($result);
    }

    /**
     * @param AdminRequest $request
     * @return array
     */
    public function create(AdminRequest $request)
    {
        $result = $this->adminServer->createAdmin($request->all());
        return $this->success($result);
    }

    /**
     * @param AdminRequest $request
     * @return array
     */
    public function update(AdminRequest $request)
    {
        $result = $this->adminServer->updateAdmin($request->all());
        return $this->success($result);
    }

    /**
     * @param AdminRequest $request
     * @return array
     */
    public function delete(AdminRequest $request)
    {
        $result = $this->adminServer->deleteAdmin($request->all());
        return $this->success($result);
    }
}