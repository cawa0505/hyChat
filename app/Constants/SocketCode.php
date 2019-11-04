<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/10
 * Time: 16:45
 */

declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class SocketCode extends AbstractConstants
{
    /**
     * @Message("success")
     */
    const SERVER_SUCCESS = 200;
}