<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 14:35
 */

namespace Zhulei\Permission\Exceptions;

use InvalidArgumentException;

class PermissionAlreadyExists extends InvalidArgumentException
{
    public static function create(string $permissionName, string $guardName)
    {
        return new static(" `{$permissionName}` 权限已经存在守卫 `{$guardName}`中了。");
    }
}