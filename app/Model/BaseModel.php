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
    /**
     * 关闭自动更新时间
     * @var bool
     */
    public $timestamps = false;

    use Cacheable;

    /**
     * 获取单条数据
     * @param $where
     * @return array|null
     */
    public function getOne($where)
    {
        return $this->newQuery()->where($where)->first()->toArray();
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

    /**
     * 获取缓存
     * @param $key
     * @return mixed
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function getCache($key)
    {
        $cacheConfig = $this->getCacheConfig($key);
        if(!$cacheConfig) return [];
        return mongoClient()->query($cacheConfig["key"])??[];
    }

    /**
     * 写入缓存
     * @param $key
     * @param $data
     * @return int|null
     */
    public function saveCache($key,$data)
    {
        $cacheConfig = $this->getCacheConfig($key);
        return mongoClient()->insert($cacheConfig["key"],$data);
    }
    /**
     * 获取缓存配置
     * @param $key
     * @return mixed
     */
    private function getCacheConfig($key)
    {
        $cacheConfig = config('api.cache_key');
        if (isset($cacheConfig[$key])) {
            return $cacheConfig[$key];
        } else {
            return $cacheConfig['defualt'];
        }
    }
    /**
     * 刷新缓存
     * @param $key
     * @return bool
     */
    public function flushCache($key)
    {
        $cacheConfig = $this->getCacheConfig($key);
        return mongoClient()->delete($cacheConfig["key"],[]);
    }
}
