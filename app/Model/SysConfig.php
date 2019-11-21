<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;
use MongoDB\Driver\Exception\Exception;

/**
 * @property string $key
 * @property int $value
 * @property string $desc
 */
class SysConfig extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_config';

    /**
     * 获取系统配置
     * @param null $key
     * @return array|mixed|null
     * @throws Exception
     */
    public function getConfig($key = null)
    {
        $data = $this->getCache("systemConfig");
        if (empty($data)) {
            $data = $this->newQuery()->get()->toArray();
            if ($data) {
                foreach ($data as $val) {
                    $this->saveCache("systemConfig", $val);
                }
            }
        }
        if (empty($key)) {
            return $data;
        }
        return isset($data[$key]) ? $data[$key] : null;
    }

    protected function createItem()
    {

    }
}