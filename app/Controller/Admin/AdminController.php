<?php


namespace App\Controller\Admin;


use App\Controller\AbstractController;
use App\Request\Admin\AdminRequest;
use App\Service\Admin\AdminService;
use Hyperf\Di\Annotation\Inject;

class AdminController extends AbstractController
{
    /**
     * @Inject()
     * @var AdminService
     */
    private $adminServer;

    public function list()
    {
        $request = $this->request->all();
        $result = $this->adminServer->getAdminList($request);
        return $this->success($result);
    }

    public function create(AdminRequest $request)
    {

    }

    public function update(AdminRequest $request)
    {

    }

    public function delete(AdminRequest $request)
    {

    }
}