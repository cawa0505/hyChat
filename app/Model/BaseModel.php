<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

/**
 * Class BaseModel
 * @package App\Model
 */
abstract class BaseModel extends Model implements CacheableInterface
{
    use Cacheable;

    /**
     * 获取单条数据
     * @param $where
     * @return array|null
     */
    public function getOne($where)
    {
        return $this->newQuery()->first()->toArray();
    }

    /**
     * 通过获取多条数据
     * @param array $whereParam
     * @return array|null
     */
    public function getMany($whereParam)
    {
        $model = $this->newQuery();

        if (is_array($whereParam)) {

            foreach ($whereParam as $key => $val) {

                if (is_array($val)) {
                    $model->where($key, $val[0],$val[1]);
                } else {
                    $model->where($key, $val);
                }
            }
        }
        return $model->get()->toArray();
    }
}
