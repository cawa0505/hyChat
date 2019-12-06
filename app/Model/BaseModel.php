<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Model\Model;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;
use Hyperf\Paginator\Paginator;
use MongoDB\Driver\Exception\Exception;
use phpDocumentor\Reflection\Types\This;

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
            if(!$model->first()){
                return [];
            }
            return $model->first()->toArray();
        }
        return [];
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
     * todo 插入一条或者多条数据
     * @param $data
     * @return bool
     */
    public function createField($data)
    {
        return $this->newQuery()->insert($data);
    }
    /**
     * todo 插入一条并获取ID
     * @param $data
     * @return bool
     */
    public function createGetId($data)
    {
        return $this->newQuery()->insertGetId($data);
    }

    /**
     * @todo 根据where条件更新数据
     * @param $where
     * @param $data
     * @return int
     */
    public function updateField($where,$data)
    {
        return $this->newQuery()->where($where)->update($data);
    }

    /**
     * todo 根据where条件删除数据
     * @param $where
     * @return int|mixed
     */
    public function deleteField($where)
    {
      return  $this->newQuery()->where($where)->delete();
    }


    /**
     * 获取缓存
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function getCache($key)
    {
        $cacheConfig = self::getCacheKey($key);
        if (!$cacheConfig) return [];
        return mongoClient()->query($cacheConfig["key"]);
    }

    /**
     * 写入缓存
     * @param $key
     * @param $data
     * @return int|null
     */
    public function saveCache($key, $data)
    {
        $cacheConfig = self::getCacheKey($key);
        return mongoClient()->insert($cacheConfig["key"], $data);
    }

    /**
     * 缓存配置键
     * @param $key
     * @return mixed
     */
    static function getCacheKey($key)
    {
        $cacheConfig = config('apiCacheKey');
        if (isset($cacheConfig[$key])) {
            return $cacheConfig[$key];
        } else {
            return $cacheConfig['default'];
        }
    }

    /**
     * 删除缓存
     * @param $key
     * @return bool
     */
    public function flushCache($key)
    {
        $cacheConfig =self::getCacheKey($key);
        return mongoClient()->delete($cacheConfig["key"], []);
    }

    /**
     * 获取分页
     * @param $query
     * @param int $size
     * @param $currentPage
     * @return mixed
     */
    public function getPage($query, $size = 10)
    {
        $data= $query->paginate($size);
        $_data["currentPage"]=$data->currentPage();
        $_data["total"]=$data->total();
        $_data["last_page"]=$data->lastPage();
        $_data["perPage"]=$data->perPage();
        $_data["data"]=$data->items();
        return $_data;
    }
}
