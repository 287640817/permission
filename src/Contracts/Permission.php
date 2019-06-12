<?php
namespace Zhulei\Permission\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Permission{

    /**
     * 权限所对应的角色
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany;

    /**
     * 通过权限名查找权限
     * @param string $name
     * @param $guardName
     * @return Permission
     */
    public static function findByName(string $name, $guardName): self;

    /**
     * 通过Id查找角色
     * @param int $id
     * @return Permission
     */
    public static function findById(int $id): self;

    /**
     * 通过角色名和守卫名查找或创建角色
     * @param string $name
     * @param $guardName
     * @return Permission
     */
    public static function findOrCreate(string $name, $guardName): self;
}