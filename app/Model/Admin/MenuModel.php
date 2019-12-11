<?php
declare(strict_types=1);

namespace App\Model\Admin;


use App\Model\BaseModel;

/**
 * TODO 菜单
 * Class MenuModel
 * @package App\Model\Admin
 */
class MenuModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = "menu";

    /**
     * @param $page
     * @param $limit
     * @param array $columns
     * @return array
     */
    public function getMenuList($page, $limit, $columns = ['*'])
    {
        $result = $this->newQuery()->forPage($page, $limit)->get($columns);
        if ($result) {
            $data = [
                'count' => $this->newQuery()->count(),
                'data' => $result->toArray(),
            ];
            return $data;
        }
        return [];
    }

    /**
     * @param $id
     * @return array
     */
    public function getMenuById($id)
    {
        $result = $this->newQuery()->where('id', $id)->first();
        if ($result) {
            return $result->toArray();
        }
        return [];
    }

    /**
     * @param $name
     * @return array
     */
    public function getMenuByName($name)
    {
        $result = $this->newQuery()->where('name', $name)->first();
        if ($result) {
            return $result->toArray();
        }
        return [];
    }
}