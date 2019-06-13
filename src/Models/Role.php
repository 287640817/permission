<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/13 0013
 * Time: 14:19
 */

namespace Zhulei\Permission\Models;

use Zhulei\Permission\Guard;
use Illuminate\Database\Eloquent\Model;
use Zhulei\Permission\Traits\HasPermissions;
use Zhulei\Permission\Exceptions\RoleDoesNotExist;
use Zhulei\Permission\Exceptions\GuardDoesNotMatch;
use Zhulei\Permission\Exceptions\RoleAlreadyExists;
use Zhulei\Permission\Contracts\Role as RoleContract;
use Zhulei\Permission\Traits\RefreshesPermissionCache;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Role implements RoleContract
{

    public static function findOrCreate(string $name, $guardName = null): RoleContract{
        $guardName = $guardName;
    }


    public function permissions(): BelongsToMany
    {

    }


    public  static function  findByName(string $name, $guardName): RoleContract
    {

    }


    public static function findById(int $id, $guardName): RoleContract{

    }

    public function hasPermissionTo($permission): bool {

    }



}