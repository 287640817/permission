<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 14:39
 */

namespace Zhulei\Permission\Exceptions;
use InvalidArgumentException;

class RoleAlreadyExists extends InvalidArgumentException
{
    public static function create(string $roleName, string $guardName)
    {
        return new static("角色 `{$roleName}` 已经存在守卫 `{$guardName}`中。");
    }
}