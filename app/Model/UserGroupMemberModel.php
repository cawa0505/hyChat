<?php

declare (strict_types=1);

namespace App\Model;

/**
 * Class UserGroupMemberModel
 * @package App\Model
 */
class UserGroupMemberModel extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_group_member';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
}