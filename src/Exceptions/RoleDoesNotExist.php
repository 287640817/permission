<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 9:29
 */

namespace Zhulei\Permission\Exceptions;

use InvalidArgumentException;

class RoleDoesNotExist extends InvalidArgumentException
{
    public static function named(string $roleName){
        return new static("没有名字为{$roleName}的角色。");
    }

    public static function withId(string $roleId){
        return new static('没有ID为{$roleId}的角色。');
    }
}