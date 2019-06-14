<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 12:26
 */

namespace Zhulei\Permission\Exceptions;


use Illuminate\Support\Collection;

class GuardDoesNotMatch extends \InvalidArgumentException
{
    public function create(string $givenGuard, Collection $expectedGuards){
        return new static("输入的权限或角色必须包含在[{$expectedGuards->implode(', ')}]而不是{$givenGuard}。");
    }
}