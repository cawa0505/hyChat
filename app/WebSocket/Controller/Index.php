<?php

namespace App\WebSocket\Controller;

use App\WebSocket\Service\CommonServer;

/**
 * Class Index
 * @package App\WebSocket\Controller
 */
class Index extends CommonServer
{
    /**
     * {"class":"Index","action":"index","content":"123456"}
     */
    public function index()
    {
        dd($this->getData());
//        $this->push($this->getFd(), $data);
    }
}