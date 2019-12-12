<?php
declare(strict_types=1);

namespace App\Controller\Admin;


use App\Controller\AbstractController;
use App\Request\Admin\MenuRequest;
use App\Service\Admin\MenuService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class MenuController extends AbstractController
{
    /**
     * @Inject()
     * @var MenuService
     */
    private $menuServer;

    /**
     * @return ResponseInterface
     */
    public function list()
    {
        $request = $this->request->all();
        $result = $this->menuServer->getMenuList($request);
        return $this->successResponse($result);
    }

    public function getUserMenu()
    {

    }

    /**
     * @param MenuRequest $request
     * @return ResponseInterface
     */
    public function create(MenuRequest $request)
    {
        $result = $this->menuServer->createMenu($request->all());
        return $this->successResponse($result);
    }

    /**
     * @param MenuRequest $request
     * @return ResponseInterface
     */
    public function update(MenuRequest $request)
    {
        $result = $this->menuServer->updateMenu($request->all());
        return $this->successResponse($result);
    }

    /**
     * @param MenuRequest $request
     * @return ResponseInterface
     */
    public function delete(MenuRequest $request)
    {
        $result = $this->menuServer->deleteMenu($request->all());
        return $this->successResponse($result);
    }
}