<?php
namespace Zhulei\Permission\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Role{
    /**
     *  获取角色的所有权限
     *  @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany;

    /**
     * 通过角色名字和守卫名查找角色
     * @param string $name
     * @param string|null $guardName
     * @return \Zhulei\Permission\Contracts\Role
     */
    public  static function  findByName(string $name, $guardName): self;

    /**
     * 通过角色ID和守卫名字查找角色
     * @param int $id
     * @param $guardName
     * @return \Zhulei\Permission\Contracts\Role
     */
    public static function findById(int $id, $guardName): self;

    /**
     * 通过角色名或者守卫名查找没有就创建角色
     * @param string $name
     * @param $guardName
     * @return Role
     */
    public static function findOrCreate(string $name, $guardName): self;

    /***
     * 判断给定的权限是否有权限执行
     * @param string|\Zhulei\Permission\Contracts\Permission $permission
     * @return bool
     */
    public function hasPermissionTo($permission): bool;
}