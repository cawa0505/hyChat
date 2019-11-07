<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

/**
 * Class BaseModel
 * @package App\Model
 */
abstract class BaseModel extends Model implements CacheableInterface
{
    /**
     * 关闭自动更新时间
     * @var bool
     */
    public $timestamps = false;

    use Cacheable;
}
