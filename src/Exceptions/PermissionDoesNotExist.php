<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/13 0013
 * Time: 9:32
 */

namespace Zhulei\Permission\Exceptions;
use InvalidArgumentException;


class PermissionDoesNotExist extends InvalidArgumentException
{
    public static function create(string $permissionName, string $guardName = ''){
        return new static("在守卫`{$guardName}`里没有`{$permissionName}`这个权限");
    }

    public static function withId(int $permissionId){
        return new static("【permission】不存在ID`{$permissionId}`");
    }
}