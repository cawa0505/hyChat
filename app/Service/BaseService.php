<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 11:10
 */

declare(strict_types=1);

namespace App\Service;


use App\Traits\PushMessage;
use App\Traits\Response;

class BaseService
{
    use Response,PushMessage;
}