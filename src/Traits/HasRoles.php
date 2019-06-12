<?php
namespace Zhulei\Permission\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Zhulei\Permission\Contracts\Role;

trait HasRoles{

    public function hasRole($roles): bool
    {
        //如果存在管道符的情况下执行
        if(is_string($roles) && false !== strpos($roles, '|')) {
            $roles = $this->convertPipeToArray($roles);
        }

        //判断角色是否包含在用户所有角色的键值对中
        if(is_string($roles)) {
            return $this->roles->contains('name', $roles);
        }

        if(is_int($roles)){
            return $this->roles->contains('id', $roles);
        }

        if($roles instanceof Role){
            return $this->roles->contains('id', $roles->id);
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
            return false;
        }
        return $roles->intersect($this->roles)->isNotEmpty();
    }

    protected function convertPipeToArray(string $pipeString) {
        $pipeString = trim($pipeString);
        if(strlen($pipeString) <= 2) {
            return $pipeString;
        }
        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($quoteCharacter, -1, 1);
        if($quoteCharacter !== $endCharacter) {
            return explode('|', $pipeString);
        }
        if(! in_array($quoteCharacter, ["'", ''])) {
            return explode('|', $pipeString);
        }
        return explode('|', trim($pipeString, $quoteCharacter));
    }

    public function roles(): MorphToMany
    {
        return $this->morphToMany(
            config('permission.models.roles'),
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            'role_id'
        );
    }
}