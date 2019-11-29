<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2019/11/4
 * Time: 11:29
 */

namespace App\Utility\Client;


use Hyperf\Server\Exception\ServerException;
use Hyperf\Task\Annotation\Task;
use Hyperf\Utils\Traits\StaticInstance;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Exception\Exception;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use MongoDB\Driver\WriteConcern;

/**
 * Class MongoModel
 * @package App\Utility\Client
 */
class MongoModel
{
    use StaticInstance;

    /**
     * 数据库名
     * @var
     */
    protected $database;

    /**
     * 表名
     * @var
     */
    protected $table;

    /**
     * 主键 _id
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * 主键类型
     * @var string
     */
    protected $keyType = 'string';

    /**
     * 自动添加时间
     * @var bool
     */
    protected $timestamps = true;

    /**
     * @var array
     */
    private $buildWhere = [];

    /**
     * @var Manager
     */
    private $manager;

    /**
     * @return Manager
     */
    private function manager()
    {
        if ($this->manager instanceof Manager) {
            return $this->manager;
        }
        $username = env('MONGODB_USERNAME', 'root');
        $password = env('MONGODB_PASSWORD', '123456');
        $host = env('MONGODB_HOST', '127.0.0.1');
        $port = env('MONGODB_PORT', '27017');
        $uri = sprintf('mongodb://%s:%s@%s:%s', $username, $password, $host, $port);
        $this->database = env("MONGODB_DATABASE", 'test');
        return $this->manager = new Manager($uri);
    }

    /**
     * 添加数据
     * @Task()
     * @param array $data 数据
     * @return int|null
     */
    public function insert(array $data)
    {
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();
        if (!isOneArray($data)) {
            foreach ($data as &$val) {
                if ($this->timestamps) {
                    $val["create_time"] = time();
                }
            }
        } else {
            if ($this->timestamps) {
                $data["create_time"] = time();
            }
        }
        $bulk->insert($data);
        $result = $this->manager()->executeBulkWrite($this->database . '.' . $this->table, $bulk, $writeConcern);
        return $result->getUpsertedCount();
    }

    /**
     * 查询数据
     * @Task()
     * @param array $filter 查询条件
     * @param int $sort 排序
     * @param int $skip offset
     * @param int $limit
     * @return array
     * @throws Exception
     */
    public function query(array $filter = [], $sort = -1, $skip = 0, $limit = 10)
    {
        $options = [
            'projection' => ['_id' => 0],
            'sort' => ['create_time' => $sort],
            'skip' => $skip,
            'limit' => $limit
        ];
        $query = new Query($filter, $options);
        $result = $this->manager()->executeQuery($this->database . '.' . $this->table, $query);
        return array_values($result->toArray());
    }

    /**
     * 查询单条
     * @Task()
     * @return array
     * @throws Exception
     */
    public function getOne()
    {
        $options = [
            'sort' => ['_id' => -1],
            'limit' => 1
        ];
        $query = new Query($this->buildWhere, $options);
        $result = $this->manager()->executeQuery($this->database . '.' . $this->table, $query);
        return array_values($result->toArray());
    }

    /**
     * 查询条件
     * @param $column
     * @param $value
     * @return $this
     */
    public function where($column, $value)
    {
        if ($this->buildWhere) {
            array_merge($this->buildWhere, [$column => $value]);
        }
        $this->buildWhere = [$column => $value];
        if (!$this->buildWhere) {
            throw new ServerException("The condition is empty");
        }
        return $this;
    }

    /**
     * @Task()
     * @return array
     * @throws Exception
     */
    public function getAll()
    {
        $options = [];
        $query = new Query($this->buildWhere, $options);
        $result = $this->manager()->executeQuery($this->database . '.' . $this->table, $query);
        return array_values($result->toArray());
    }

    /**
     * 修改数据
     * @Task()
     * @param array $data 更改后数据
     * @param array $options 附加条件
     * @return int|null
     */
    public function update(array $data, array $options = [])
    {
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();
        $bulk->update($this->buildWhere, $data, $options);
        $result = $this->manager()->executeBulkWrite($this->database . '.' . $this->table, $bulk, $writeConcern);
        return $result->getModifiedCount();
    }

    /**
     * 删除数据
     * @Task()
     * @param array $options 附加条件
     * @return int|null
     */
    public function delete(array $options = [])
    {
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();
        $bulk->delete($this->buildWhere, $options);
        $result = $this->manager()->executeBulkWrite($this->database . '.' . $this->table, $bulk, $writeConcern);
        return $result->getDeletedCount();
    }
}