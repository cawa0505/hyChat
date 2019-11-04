<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/10
 * Time: 16:57
 */

namespace App\Traits;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\CoroutineHandler;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait Request
 * @package App\Traits
 */
trait Request
{
    /**
     * @param $host
     * @param $uri
     * @param array $data
     * @return mixed|ResponseInterface
     */
    public function requestPost($host, $uri, $data = [])
    {
        $client = new Client([
            'base_uri' => $host,
            'handler' => HandlerStack::create(new CoroutineHandler()),
            'timeout' => 5,
        ]);
        $options['headers'] = ["Accept" => "text/plain;charset=utf-8", "Content-Type" => "application/x-www-form-urlencoded;charset=utf-8"];
        $options['form_params'] = $data;
        return $client->post($uri, $options)->getBody()->getContents();
    }

    /**
     * @param $host
     * @param $uri
     * @param $body
     * @return string
     */
    public function requestPostJson($host, $uri, $body)
    {
        $client = new Client([
            'base_uri' => $host,
            'handler' => HandlerStack::create(new CoroutineHandler()),
            'timeout' => 5,
        ]);
        $options['headers'] = ["Content-Type" => "application/json"];
        $options['body'] = json_encode($body);
        return $client->post($uri, $options)->getBody()->getContents();
    }

    /**
     * @param $host
     * @param $uri
     * @param array $params
     * @return string
     */
    public function requestGet($host, $uri, $params = [])
    {
        $client = new Client([
            'base_uri' => $host,
            'handler' => HandlerStack::create(new CoroutineHandler()),
            'timeout' => 5,
        ]);
        return $client->get($uri, ['query' => $params])->getBody()->getContents();
    }
}