<?php

declare (strict_types=1);

namespace App\Model;

/**
 * Class UserModel
 * @package App\Model
 */
class UserModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'user';

    /**
     * @param $account
     * @return array|null
     */
    public function searchUserByAccount($account)
    {
        $user = $this->newQuery()->where('account', 'like', "$account%")->get(['id', 'account', 'nick_name', 'status', 'image_url']);
        if ($user) {
            return $user->toArray();
        }

        return [];
    }

    /**
     * @param $account
     * @param array $columns
     * @return array|null
     */
    public function getUserByAccount($account, $columns = ['*'])
    {
        $user = $this->newQuery()->where('account', $account)->first($columns);
        if ($user) {
            return $user->toArray();
        }

        return [];
    }

    /**
     * @param $userIds
     * @param array $columns
     * @return array
     */
    public function getUserByUserIds($userIds, $columns = ['*'])
    {
        $user = $this->newQuery()->whereIn('id', $userIds)->get($columns);
        if ($user) {
            return $user->toArray();
        }

        return [];
    }

    /**
     * @param $userId
     * @param array $columns
     * @return array|null
     */
    public function getUserByUserId($userId, $columns = ['*'])
    {
        $user = $this->newQuery()->where('id', $userId)->first($columns);
        if ($user) {
            return $user->toArray();
        }

        return [];
    }

    /**
     * 创建账户
     * @param $data
     * @return bool
     */
    public function createAccount($data)
    {
        return $this->newQuery()->insert($data);
    }

    /**
     * 通过手机号修改密码
     * @param $phone
     * @param $password
     * @return bool
     */
    public function updatePasswordByPhone($phone, $password)
    {
        return $this->newQuery()->where('phone', $phone)->update(['password' => $password]);
    }
}