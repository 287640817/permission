<?php
namespace Zhulei\Permission\Models;

use Zhulei\Permission\Guard;
use Illuminate\Database\Eloquent\Model;
use Zhulei\Permission\Traits\HasPermissions;
use Zhulei\Permission\Traits\RefreshesPermissionCache;
use Zhulei\Permission\Exceptions\RoleDoesNotExist;
use Zhulei\Permission\Exceptions\GuardDoesNotMatch;
use Zhulei\Permission\Exceptions\RoleAlreadyExists;
use Zhulei\Permission\Contracts\Role as RoleContract;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model implements RoleContract
{
    use HasPermissions;
    use RefreshesPermissionCache;

    protected $guarded = ['id'];

    public static function findOrCreate(string $name, $guardName = null): RoleContract
    {
        $guardName = self::getGuardName($guardName);
        $role = static::where('name', $name)->where('guard_name', $guardName)->first();
        if(! $role){
            return static::query()->create(['name' => $name, 'guard_name' => $guardName]);
        }
        return $role;
    }

    protected static function getGuardName($guardName = null){
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        return $guardName;
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