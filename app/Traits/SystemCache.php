<?php

declare(strict_types=1);

namespace App\Traits;


use MongoDB\Driver\Exception\Exception;

trait SystemCache
{
    /**
     * Todo 获取缓存
     * @param $key
     * @return array
     */
    public function getCache($key)
    {
        $cacheConfig = $this->getCacheKey($key);
        if (!$cacheConfig) return [];
        return mongoModel()->table($cacheConfig["key"])->query();
    }

    /**
     * Todo 写入缓存
     * @param $key
     * @param $data
     * @return int|null
     */
    public function saveCache($key, $data)
    {
        $cacheConfig = $this->getCacheKey($key);
        return mongoModel()->table($cacheConfig["key"])->insert($data);
    }

    /**
     * Todo 缓存配置键
     * @param $key
     * @return mixed
     */
    public function getCacheKey($key)
    {
        $cacheConfig = config('apiCacheKey');
        if (isset($cacheConfig[$key])) {
            return $cacheConfig[$key];
        } else {
            return $cacheConfig['default'];
        }
    }

    /**
     * Todo 删除缓存
     * @param $key
     * @return bool
     */
    public function flushCache($key)
    {
        $cacheConfig = $this->getCacheKey($key);
        return mongoModel()->table($cacheConfig["key"])->delete();
    }
}