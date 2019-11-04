<?php

namespace App\WebSocket\Controller;

use App\WebSocket\Common;

/**
 * Class Index
 * @package App\WebSocket\Controller
 */
class Index extends Common
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