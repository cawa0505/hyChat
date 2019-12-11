<?php


namespace App\Model;


use App\Utility\Client\MongoModel;

class UserMessageModel extends MongoModel
{
    /**
     * 表名
     * @var
     */
    protected $table = "user_message";
}