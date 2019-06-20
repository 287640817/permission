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

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');
        parent::__construct($attributes);
        $this->setTable(config('permission.table_names.roles'));
    }

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);
        if (static::where('name', $attributes['name'])->where('guard_name', $attributes['guard_name'])->first()) {
            throw RoleAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }
        if (isNotLumen() && app()::VERSION < '5.4') {
            return parent::create($attributes);
        }
        return static::query()->create($attributes);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            'role_id',
            'permission_id'
        );
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            config('permission.table_names.model_has_roles'),
            'role_id',
            config('permission.column_names.model_morph_key')
        );
    }

    public static function findOrCreate(string $name, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $role = static::where('name', $name)->where('guard_name', $guardName)->first();
        if (! $role) {
            return static::query()->create(['name' => $name, 'guard_name' => $guardName]);
        }
        return $role;
    }


    protected static function getGuardName($guardName = null){
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        return $guardName;
    }


    public static function findByName(string $name, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $role = static::where('name', $name)->where('guard_name', $guardName)->first();
        if (! $role) {
            throw RoleDoesNotExist::named($name);
        }
        return $role;
    }


    public static function findById(int $id, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $role = static::where('id', $id)->where('guard_name', $guardName)->first();
        if (! $role) {
            throw RoleDoesNotExist::withId($id);
        }
        return $role;
    }


    public function hasPermissionTo($permission): bool
    {
        $permissionClass = $this->getPermissionClass();
        if (is_string($permission)) {
            $permission = $permissionClass->findByName($permission, $this->getDefaultGuardName());
        }
        if (is_int($permission)) {
            $permission = $permissionClass->findById($permission, $this->getDefaultGuardName());
        }
        if (! $this->getGuardNames()->contains($permission->guard_name)) {
            throw GuardDoesNotMatch::create($permission->guard_name, $this->getGuardNames());
        }
        return $this->permissions->contains('id', $permission->id);
    }



}