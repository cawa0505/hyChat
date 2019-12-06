<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2019/11/4
 * Time: 11:29
 */

namespace App\Utility\Client;

use Hyperf\Pool\Channel;
use Hyperf\Utils\Traits\StaticInstance;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\BulkWrite;
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
        $test = mongoModel()->table("admin")->delete();
        dd($test);

        for ($i = 1; $i < 10; $i++) {
        TestModel::instance()->insert(['username' => 'test' . $i]);
        }
        $result = mongoModel()->table("admin")->where('username', "test6")->update(['username' => "admin"]);
        var_dump($result);

        $result3 = mongoModel()->table("admin")->where('username', "test5")->delete();
        var_dump($result3);

        $result1 = mongoModel()->table("admin")->where('username', "admin")->getOne();
        var_dump($result1);

        $result2 = mongoModel()->table("admin")->find("5dea0f5246f4e5135d68d282");
        var_dump($result2);
     */


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
     *  TODO 查询条件
     * @param $key
     * @param $value
     * @param string $operator
     * @return $this
     */
    public function where($key, $value, $operator = "=")
    {
        switch ($operator) {
            case '=':
                $where = [$key => $value];
                break;
            case '>':
                $where = [$key => ['$gt' => $value]];
                break;
            case '>=':
                $where = [$key => ['$gte' => $value]];
                break;
            case '<':
                $where = [$key => ['$lt' => $value]];
                break;
            case '<=':
                $where = [$key => ['$lte' => $value]];
                break;
            case '!=':
                $where = [$key => ['$ne' => $value]];
                break;
            default:
                $where = [];
                break;
        }
        if ($this->buildWhere) {
            $this->buildWhere = array_merge($this->buildWhere, $where);
        } else {
            $this->buildWhere = $where;
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
            $this->buildWhere = array_merge($this->buildWhere, $where);
        } else {
            $this->buildWhere = $where;
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
            $this->buildWhere = array_merge($this->buildWhere, $where);
        } else {
            $this->buildWhere = $where;
        }
        return $this;
    }

    /**
     * TODO 添加数据
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
        $channel = new \Swoole\Coroutine\Channel();
        go(function () use ($channel, $bulk, $writeConcern) {
            $result = $this->manager()->executeBulkWrite($this->database . '.' . $this->table, $bulk, $writeConcern);
            $channel->push($result->getInsertedCount());
        });
        return $channel->pop();
    }

    /**
     * TODO 查询数据
     * @param array $filter 查询条件
     * @param int $sort 排序
     * @param int $skip offset
     * @param int $limit
     * @return array
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
        $channel = new \Swoole\Coroutine\Channel();
        go(function () use ($channel, $query) {
            $result = $this->manager()->executeQuery($this->database . '.' . $this->table, $query);
            $channel->push($result->toArray());
        });
        return $channel->pop();
    }

    /**
     * TODO 查询单条
     * @return array
     */
    public function getOne()
    {
        $options = [
            'sort' => ['create_time' => -1],
            'limit' => 1
        ];
        $query = new Query($this->buildWhere, $options);
        $channel = new Channel(1);
        go(function () use ($channel, $query) {
            $result = $this->manager()->executeQuery($this->database . '.' . $this->table, $query);
            $channel->push($result->toArray());
        });
        $result = $channel->pop(0.1);
        return $this->parseId($result);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $query = new Query(["_id" => new \MongoDB\BSON\ObjectID($id)]);
        $channel = new \Swoole\Coroutine\Channel();
        go(function () use ($channel, $query) {
            $result = $this->manager()->executeQuery($this->database . '.' . $this->table, $query);
            $channel->push($result->toArray());
        });
        $result = $channel->pop();
        return $this->parseId($result);
    }


    /**
     * TODO 查询全部
     * @return array
     */
    public function getAll()
    {
        $options = ['sort' => ['create_time' => 1]];
        $query = new Query($this->buildWhere, $options);
        $channel = new \Swoole\Coroutine\Channel();
        go(function () use ($channel, $query) {
            $result = $this->manager()->executeQuery($this->database . '.' . $this->table, $query);
            $channel->push($result->toArray());
        });
        return $this->parseId($channel->pop());
    }

    /**
     * TODO 修改数据
     * @param array $data 更改后数据
     * 如果条件不成立，则新增数据，如果要设置条件不成立不增加可以设置'upsert' => false
     * multi默认为true,表示满足条件全部修改，false表示只修改满足条件的第一条
     * @return int|null
     */
    public function update(array $data)
    {
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();
        $options = ['multi' => false, 'upsert' => false];
        $bulk->update($this->buildWhere, ['$set' => $data], $options);
        $channel = new \Swoole\Coroutine\Channel();
        go(function () use ($channel, $bulk, $writeConcern) {
            $result = $this->manager()->executeBulkWrite($this->database . '.' . $this->table, $bulk, $writeConcern);
            $channel->push($result->getModifiedCount());
        });
        return $channel->pop();
    }

    /**
     * TODO 删除数据
     * @return int|null
     */
    public function delete()
    {
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();
        // limit 为 1 时，删除第一条匹配数据  limit 为 0 时，删除所有匹配数据，默认删除所有
        $options = ['limit' => 0];
        $bulk->delete($this->buildWhere, $options);
        $channel = new \Swoole\Coroutine\Channel();
        go(function () use ($channel, $bulk, $writeConcern) {
            $result = $this->manager()->executeBulkWrite($this->database . '.' . $this->table, $bulk, $writeConcern);
            $channel->push($result->getDeletedCount());
        });
        return $channel->pop();
    }

    /**
     * 解析数据组中的'_id'字段
     * @param $arr
     * @return mixed
     */
    private function parseId($arr)
    {
        if (!empty($arr)) {
            foreach ($arr as $key => &$item) {
                if ($item->_id instanceof ObjectID) {
                    $item->_id = $item->_id->__toString();
                }
                $item = (array)$item;
            }
        }
        return $arr;
    }
}