<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2019/11/4
 * Time: 11:29
 */

namespace App\Utility\Client;


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
    private $database;

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
     * @param string $table
     * @return $this
     */
    public function table(string $table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * TODO 查询条件
     * @param $key
     * @param $operator
     * @param $value
     * @return $this
     */
    public function where($key, $operator, $value)
    {
        $filter = [];
        switch ($operator) {
            case '=':
                $filter = [$key => $value];
                break;
            case '>':
                $filter = [$key => ['$gt' => $value]];
                break;
            case '>=':
                $filter = [$key => ['$gte' => $value]];
                break;
            case '<':
                $filter = [$key => ['$lt' => $value]];
                break;
            case '<=':
                $filter = [$key => ['$lte' => $value]];
                break;
            case '!=':
                $filter = [$key => ['$ne' => $value]];
                break;
            default:
                break;
        }
        if ($this->buildWhere) {
            array_merge($this->buildWhere, $filter);
        } else {
            $this->buildWhere[] = $filter;
        }
        return $this;
    }

    /**
     * @param $column
     * @param array $value
     * @return $this
     */
    public function whereIn($column, array $value)
    {
        $where = [$column => ['$in' => $value]];
        if ($this->buildWhere) {
            array_merge($this->buildWhere, $where);
        } else {
            $this->buildWhere[] = $where;
        }
        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function whereOr($column, $value)
    {
        $where = [$column => ['$or' => $value]];
        if ($this->buildWhere) {
            array_merge($this->buildWhere, $where);
        } else {
            $this->buildWhere[] = $where;
        }
        return $this;
    }

    /**
     * TODO 添加数据
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
                    $val["status"] = 1;
                    $val["create_time"] = time();
                }
            }
        } else {
            if ($this->timestamps) {
                $data["status"] = 1;
                $data["create_time"] = time();
            }
        }
        $bulk->insert($data);
        $result = $this->manager()->executeBulkWrite($this->database . '.' . $this->table, $bulk, $writeConcern);
        return $result->getUpsertedCount();
    }

    /**
     * TODO 查询数据
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
     * TODO 查询单条
     * @Task()
     * @return array
     * @throws Exception
     */
    public function getOne()
    {
        $options = [
            'sort' => ['create_time' => -1],
            'limit' => 1
        ];
        $query = new Query($this->buildWhere, $options);
        $result = $this->manager()->executeQuery($this->database . '.' . $this->table, $query);
        return array_values($result->toArray());
    }


    /**
     * TODO 查询全部
     * @Task()
     * @return array
     * @throws Exception
     */
    public function getAll()
    {
        $options = [
            'projection' => ['_id' => 0],
            'sort' => ['create_time' => 1],
        ];
        $query = new Query($this->buildWhere, $options);
        $result = $this->manager()->executeQuery($this->database . '.' . $this->table, $query);
        return array_values($result->toArray());
    }

    /**
     * TODO 修改数据
     * @Task()
     * @param array $data 更改后数据
     * 如果条件不成立，则新增数据，如果要设置条件不成立不增加可以设置'upsert' => false
     * multi默认为true,表示满足条件全部修改，false表示只修改满足条件的第一条
     * @return int|null
     */
    public function update(array $data)
    {
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();
        $options = ['multi' => true, 'upsert' => false];
        $bulk->update($this->buildWhere, $data, $options);
        $result = $this->manager()->executeBulkWrite($this->database . '.' . $this->table, $bulk, $writeConcern);
        return $result->getModifiedCount();
    }

    /**
     * TODO 删除数据
     * @Task()
     * @return int|null
     */
    public function delete()
    {
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();
        // limit 为 1 时，删除第一条匹配数据  limit 为 0 时，删除所有匹配数据，默认删除所有
        $options = ['limit' => 0];
        $bulk->delete($this->buildWhere, $options);
        $result = $this->manager()->executeBulkWrite($this->database . '.' . $this->table, $bulk, $writeConcern);
        return $result->getDeletedCount();
    }
}