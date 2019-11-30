<?php

declare(strict_types=1);

namespace App\Model;

use App\Traits\SystemCache;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Model\Model;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

/**
 * Class BaseModel
 * @package App\Model
 */
abstract class BaseModel extends Model implements CacheableInterface
{
    /**
     * Todo 关闭自动更新时间
     * @var bool
     */
    public $timestamps = false;

    use Cacheable, SystemCache;

    /**
     * Todo 获取单条数据
     * @param $where
     * @return array|Builder|\Hyperf\Database\Model\Model|object|null
     */
    public function getOne($where)
    {
        $model = $this->newQuery();
        if (is_array($where)) {
            foreach ($where as $key => $val) {

                if (is_array($val)) {
                    $model->where($key, $val[0], $val[1]);
                } else {
                    $model->where($key, $val);
                }

            }
            if (!$model->first()) {
                return [];
            }
            return $model->first()->toArray();
        }
        return [];
    }

    /**
     * Todo 通过获取多条数据
     * @param array $whereParam
     * @return array|null
     */
    public function getMany($whereParam)
    {
        $model = $this->newQuery();

        if (is_array($whereParam)) {

            foreach ($whereParam as $key => $val) {

                if (is_array($val)) {
                    $model->where($key, $val[0], $val[1]);
                } else {
                    $model->where($key, $val);
                }
            }
            return $model->get()->toArray();
        }
        return [];

    }

    /**
     * Todo 插入一条或者多条数据
     * @param $data
     * @return bool
     */
    public function createField($data)
    {
        return $this->newQuery()->insert($data);
    }

    /**
     * Todo 根据where条件更新数据
     * @param $where
     * @param $data
     * @return int
     */
    public function updateField($where, $data)
    {
        return $this->newQuery()->where($where)->update($data);
    }

    /**
     * Todo 根据where条件删除数据
     * @param $where
     * @return int|mixed
     */
    public function deleteField($where)
    {
        return $this->newQuery()->where($where)->delete();
    }
}
