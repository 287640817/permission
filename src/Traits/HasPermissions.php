<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 10:26
 */

namespace Zhulei\Permission\Traits;


use Illuminate\Support\Collection;
use Zhulei\Permission\Contracts\Permission;
use Zhulei\Permission\Exceptions\GuardDoesNotMatch;
use Zhulei\Permission\Exceptions\PermissionDoesNotExist;
use Zhulei\Permission\Guard;
use Zhulei\Permission\PermissionRegistrar;

trait HasPermissions
{
    private $permissionClass;

    public function permissions(): MorphToMany
    {
        return $this->morphToMany(
            config('permission.models.permission'),
            'model',
            config('permission.table_names.model_has_permissions'),
            config('permission.column_names.model_morph_key'),
            'permission_id'
        );
    }

    public function givePermissionTo(...$permissions){
        $permissions = collect($permissions)
            ->flatten()->map(function($permission){
                if(empty($permission)){
                    return false;
                }
            })->filter(function($permission){
                return $permission instanceof Permission;
            })->each(function($permission){
                $this->ensureModelSharesGuard($permission);
            })->map->id->all();

    }

    protected function getStoredPermission($permissions){
        $permissionClass = $this->getPermissionClass();
        if(is_numeric($permissions)){
            return $permissionClass->findById('id', $permissions);
        }
        if(is_string($permissions)){
            return $permissionClass->findByName('name', $permissions);
        }

        if(is_array($permissions)){
            return $permissionClass
                ->whereIn('name', $permissions)
                ->whereIn('guard_name', $this->getGuardNames())
                ->get();
        }

    }


    public function getPermissionClass(): Permission
    {
        if(! isset($this->permissionClass)){
            $this->permissionClass = app(PermissionRegistrar::class)->getPermissionClass();
        }

        return $this->permissionClass;
    }

    protected function getGuardNames(): Collection
    {
        return Guard::getNames($this);
    }

    protected function ensureModelSharesGuard($roleOrPermission){
        if(! $this->getGuardNames()->contains($roleOrPermission->guard_name)){
            throw GuardDoesNotMatch::create($roleOrPermission->guard_name, $this->getGuardNames());
        }
    }

    public function hasAnyPermission(...$permissions): bool
    {
        if(is_array($permissions[0])){
            $permissions = $permissions[0];
        }

        foreach($permissions as $permission){
            if($this->checkPermissionTo($permission)){
                return true;
            }
        }
        return false;
    }

    public function checkPermissionTo($permission, $guardName = null): bool
    {
        try{
            return $this->hasPermissionTo($permission, $guardName);
        }catch(PermissionDoesNotExist $e){
            return false;
        }
    }

    public function hasPermissionTo($permission, $guardName = null): bool
    {
        $permissionClass = $this->getPermissionClass();
        if (is_string($permission)) {
            $permission = $permissionClass->findByName(
                $permission,
                $guardName ?? $this->getDefaultGuardName()
            );
        }
        if (is_int($permission)) {
            $permission = $permissionClass->findById(
                $permission,
                $guardName ?? $this->getDefaultGuardName()
            );
        }
        if (! $permission instanceof Permission) {
            throw new PermissionDoesNotExist;
        }
        return $this->hasDirectPermission($permission) || $this->hasPermissionViaRole($permission);
    }

    public function hasDirectPermission($permission): bool
    {
        $permissionClass = $this->getPermissionClass();
        if (is_string($permission)) {
            $permission = $permissionClass->findByName($permission, $this->getDefaultGuardName());
            if (! $permission) {
                return false;
            }
        }
        if (is_int($permission)) {
            $permission = $permissionClass->findById($permission, $this->getDefaultGuardName());
            if (! $permission) {
                return false;
            }
        }
        if (! $permission instanceof Permission) {
            return false;
        }
        return $this->permissions->contains('id', $permission->id);
    }

    protected function getDefaultGuardName(): string
    {
        return Guard::getDefaultName($this);
    }

    protected function hasPermissionViaRole(Permission $permission): bool
    {
        return $this->hasRole($permission->roles);
    }
}