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
use Illuminate\Support\Collection;
use Zhulei\Permission\Contracts\Permission as PermissionContracts;
use Zhulei\Permission\Exceptions\PermissionDoesNotExist;
use Zhulei\Permission\Guard;
use Zhulei\Permission\PermissionRegistrar;

class Permission extends Model implements PermissionContracts
{

    public static function findOrCreate(string $name, $guardName): PermissionContracts{
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermissions(['name' => $name, 'guard_name' => $guardName])->first();
        if(! $permission){
//            throw
        }
    }

    public function roles(): BelongsToMany
    {

    }


    public static function findByName(string $name, $guardName = null): PermissionContracts
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermissions(['name' => $name, 'guard_name' => $guardName])->first();
        if (! $permission) {
            throw PermissionDoesNotExist::create($name, $guardName);
        }
        return $permission;
    }


    public static function findById(int $id, $guardName = null): PermissionContracts
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermissions(['id' => $id, 'guard_name' => $guardName])->first();
        if (! $permission) {
            throw PermissionDoesNotExist::withId($id, $guardName);
        }
        return $permission;
    }

    protected static function getPermissions(array $params = []):Collection
    {
        return app(PermissionRegistrar::class)->getPermissions($params);
    }

}