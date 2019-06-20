<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/13 0013
 * Time: 14:19
 */
namespace Zhulei\Permission\Models;

use Zhulei\Permission\Guard;
use Illuminate\Support\Collection;
use Zhulei\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Zhulei\Permission\PermissionRegistrar;
use Zhulei\Permission\Traits\RefreshesPermissionCache;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Zhulei\Permission\Exceptions\PermissionDoesNotExist;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Zhulei\Permission\Exceptions\PermissionAlreadyExists;

use Zhulei\Permission\Contracts\Permission as PermissionContracts;


class Permission extends Model implements PermissionContracts
{

    use HasRoles;
    use RefreshesPermissionCache;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ??  config('auth.defaults.guard');
        parent::__construct($attributes);

        $this->setTable(config('permission.table_names.permissions'));
    }

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);
        $permission = static::getPermissions(['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']])->first();
        if ($permission) {
            throw PermissionAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }
        if (isNotLumen() && app()::VERSION < '5.4') {
            return parent::create($attributes);
        }
        return static::query()->create($attributes);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.tables_names.role_has_permissions'),
            'permission_id',
            'role_id'
        );
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            config('permission.table_names.model_has_permissions'),
            'permission_id',
            config('permission.column_names.model_morph_key')
        );
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

    public static function findOrCreate(string $name, $guardName = null): PermissionContracts
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermissions(['name' => $name, 'guard_name' => $guardName])->first();
        if (! $permission) {
            return static::query()->create(['name' => $name, 'guard_name' => $guardName]);
        }
        return $permission;
    }

    protected static function getPermissions(array $params = []):Collection
    {
        return app(PermissionRegistrar::class)->getPermissions($params);
    }

}