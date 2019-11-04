<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2019/11/4
 * Time: 11:29
 */

namespace App\Utility;


use Hyperf\Task\Annotation\Task;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Exception\Exception;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use MongoDB\Driver\WriteConcern;

class MongoClient
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * @return Manager
     */
    protected function manager()
    {
        if ($this->manager instanceof Manager) {
            return $this->manager;
        }
        $username = env('MONGODB_USERNAME', 'root');
        $password = env('MONGODB_PASSWORD', '123456');
        $host = env('MONGODB_HOST', '127.0.0.1');
        $port = env('MONGODB_PORT', '27017');
        $uri = sprintf('mongodb://%s:%s@%s:%s', $username, $password, $host, $port);
        return $this->manager = new Manager($uri);
    }

    /**
     * 添加数据
     * @Task()
     * @param string $namespace 表名
     * @param array $document 数据
     * @return int|null
     */
    public function insert(string $namespace, array $document)
    {
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();
        $bulk->insert($document);

        $result = $this->manager()->executeBulkWrite($namespace, $bulk, $writeConcern);
        return $result->getUpsertedCount();
    }

    /**
     * 查询数据
     * @Task()
     * @param string $namespace 表名
     * @param array $filter 查询条件
     * @param array $options 附加条件
     * @return array
     * @throws Exception
     */
    public function query(string $namespace, array $filter = [], array $options = [])
    {
        $query = new Query($filter, $options);
        $result = $this->manager()->executeQuery($namespace, $query);
        return $result->toArray();
    }

    /**
     * 修改数据
     * @Task()
     * @param string $namespace 表名
     * @param array $filter 更改条件
     * @param array $data 更改后数据
     * @param array $options 附加条件
     * @return int|null
     */
    public function update(string $namespace, array $filter, array $data, array $options = [])
    {
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();
        $bulk->update($filter, $data, $options);
        $result = $this->manager()->executeBulkWrite($namespace, $bulk, $writeConcern);
        return $result->getModifiedCount();
    }

    /**
     * 删除数据
     * @Task()
     * @param string $namespace 表名
     * @param array $filter 删除条件
     * @param array $options 附加条件
     * @return int|null
     */
    public function delete(string $namespace, array $filter, array $options = [])
    {
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();
        $bulk->delete($filter, $options);
        $result = $this->manager()->executeBulkWrite($namespace, $bulk, $writeConcern);
        return $result->getDeletedCount();
    }
}