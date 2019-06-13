<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/13 0013
 * Time: 14:19
 */
namespace Zhulei\Permission\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Zhulei\Permission\Contracts\Permission as PermissionContracts;

class Permission extends Model implements PermissionContracts
{

    public function roles(): BelongsToMany
    {

    }


    public static function findByName(string $name, $guardName): PermissionContracts{

    }


    public static function findById(int $id): PermissionContracts{

    }

    public static function findOrCreate(string $name, $guardName): PermissionContracts{

    }
}