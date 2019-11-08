<?php

namespace App\WebSocket\Controller;


/**
 * Class Index
 * @package App\WebSocket\Controller
 */
class IndexController extends BaseController
{
    /**
     *
     */
    public function index()
    {
        $data = $this->getData();
        $this->sendToUser($data['userId'], $data['message']);
    }
}