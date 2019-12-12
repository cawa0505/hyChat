<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Constants\AdminCode;
use App\Model\Admin\MenuModel;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;

/**
 * Class MenuService
 * @package App\Service\Admin
 */
class MenuService extends BaseService
{
    /**
     * @Inject()
     * @var MenuModel
     */
    private $menuModel;

    /**
     * 角色列表
     * @param $request
     * @return array
     */
    public function getMenuList($request)
    {
        $page = isset($request['page']) ? $request['page'] : 1;
        $limit = isset($request['limit']) ? $request['page'] : 10;
        $result = $this->menuModel->getMenuList($page, $limit);
        return $this->success($result);
    }

    public function getMenuBuUserId($userId)
    {

    }

    /**
     * 创建角色
     * @param $request
     * @return array
     */
    public function createMenu($request)
    {
        $menuResult = $this->menuModel->getMenuByName($request['name']);
        if ($menuResult) {
            return $this->fail(AdminCode::ALREADY_EXISTS);
        }
        $saveData = [
            'name' => $request['name'],
            'url' => $request['url'],
            'identify' => $request['identify'],
            'parent_id' => $request['parent_id'],
            'icon' => $request['icon'],
            'create_time' => time()
        ];
        $result = $this->menuModel->newQuery()->insert($saveData);
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
    public function updateMenu($request)
    {
        $menuResult = $this->menuModel->getMenuByName($request['name']);
        if ($menuResult && $request['id'] != $menuResult['id']) {
            return $this->fail(AdminCode::ALREADY_EXISTS);
        }
        $saveData = [
            'id' => $request['id'],
            'name' => $request['name'],
            'url' => $request['url'],
            'identify' => $request['identify'],
            'parent_id' => $request['parent_id'],
            'icon' => $request['icon'],
            'update_time' => time()
        ];
        $result = $this->menuModel->newQuery()->where("id", $request['id'])->update($saveData);
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
    public function deleteMenu($request)
    {
        $menuResult = $this->menuModel->getMenuById($request['id']);
        if (!$menuResult) {
            return $this->fail(AdminCode::DELETE_ERROR);
        }
        if (is_array($request['id'])) {
            $result = $this->menuModel->newQuery()->whereIn('id', $request['id'])->delete();
        } else {
            $result = $this->menuModel->newQuery()->where('id', $request['id'])->delete();
        }
        if (!$result) {
            return $this->fail(AdminCode::DELETE_ERROR);
        }
        return $this->success($menuResult);
    }
}