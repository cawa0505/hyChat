<?php
/**
 * Created by PhpStorm.
 * User: PhpStorm
 * Date: 2019/11/4
 * Time: 11:37
 */

namespace App\Utility\Client;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Hyperf\Guzzle\RingPHP\PoolHandler;
use Hyperf\Task\Annotation\Task;
use Swoole\Coroutine;

/**
 * Class ElasticSearch
 * @package App\Utility
 */
class ElasticSearchClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @return Client
     */
    protected function Client()
    {
        if ($this->client instanceof Client) {
            return $this->client;
        }

        $builder = ClientBuilder::create();
        if (Coroutine::getCid() > 0) {
            $handler = make(PoolHandler::class, [
                'option' => [
                    'max_connections' => 50,
                ],
            ]);
            $builder->setHandler($handler);
        }

        return $builder->setHosts(['http://127.0.0.1:9200'])->build();
    }

    /**
     * 创建一个索引（index,类似于创建一个库）
     * @Task()
     * @param $index
     * @return array|callable
     */
    public function createIndex($index)
    {
        $params = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 1
                ]
            ]
        ];
        $response = $this->client->indices()->create($params);

        return $response;
    }

    /**
     * 删除一个索引（index,类似于删除一个库）
     * @Task()
     * @param $index
     * @return array|callable
     */
    public function deleteIndex($index)
    {
        $params = [
            'index' => $index
        ];
        $response = $this->client->indices()->delete($params);

        return $response;
    }

    /**
     * 创建一条数据（索引一个文档）
     * @Task()
     * @param $index
     * @param $type
     * @param $id
     * @param $body
     * @return array|callable
     */
    public function createDoc($index, $type, $id, $body)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'body' => $body,
        ];
        $response = $this->client->index($params);

        return $response;
    }

    /**
     * @Task()
     * 获取一个文档（对应上面createDoc）
     * @param $index
     * @param $type
     * @param $id
     * @return array|callable
     */
    public function getDoc($index, $type, $id)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id
        ];
        $response = $this->client->get($params);

        return $response;
    }

    /**
     * 搜索文档单字段
     * @Task()
     * @param $index
     * @param $type
     * @param $query
     * @return mixed
     */
    public function searchOne($index, $type, array $query)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'body' => [
                'query' => [
                    'match' => [
                        $query
                    ]
                ]
            ]
        ];
        $response = $this->client->search($params);

        return $response['hits'];
    }

    /**
     * 搜索文档多字段
     * @Task()
     * @param $index
     * @param $type
     * @param $query
     * @param array $fields
     * @return mixed
     */
    public function searchMore($index, $type, $query, array $fields)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        "type" => "best_fields",
                        'operator' => 'or',
                        'fields' => $fields
                    ]
                ]
            ]
        ];
        $response = $this->client->search($params);

        return $response['hits'];
    }

    /**
     * 删除一条记录（文档
     * @Task()
     * @param $index
     * @param $type
     * @param $id
     * @return array|callable
     */
    public function delete($index, $type, $id)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id
        ];
        $response = $this->client->delete($params);

        return $response;
    }

}