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
     * 通过手机号和账号搜索用户
     * @param $account
     * @return array|null
     */
    public function searchUserByAccount($account)
    {
        $user = $this->newQuery()
            ->orWhere('account', 'like', "$account%")
            ->orWhere("phone", 'like', "$account%")
            ->get(['id', 'account', 'phone', 'nick_name', 'status', 'image_url']);
        if ($user) {
            return $user->toArray();
        }

        return [];
    }

    /**
     * 通过手机号或账号获取用户信息
     * @param $account
     * @param array $columns
     * @return array|null
     */
    public function getUserByAccount($account, $columns = ['*'])
    {
        $user = $this->newQuery()->where('account', $account)->orWhere('phone', $account)->first($columns);
        if ($user) {
            return $user->toArray();
        }

        return [];
    }


    /**
     * 通过用户ids 获取用户信息
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
     * 通过用户id获取用户信息
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

    /**
     * 更新用户信息
     * @param $data
     * @param $userId
     * @return int
     */
    public function updateUserInfo($data, $userId)
    {

        return $this->newQuery()->where("id", $userId)->update($data);
    }

}