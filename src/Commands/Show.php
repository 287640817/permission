<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/19 0019
 * Time: 15:44
 */
namespace Zhulei\Permission\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Zhulei\Permission\Models\Role;
use Zhulei\Permission\Models\Permission;

class Show extends Command{
    protected $signature = "permission:show
                            {guard? : 权限守卫名}
                            {style? : 显示样式(default|borderless|compact|box)}";
    public function handle(){
        $style = $this->argument('style') ?? 'default';
        $guard = $this->argument('guard');
        if($guard){
            $guards = Collection::make([$guard]);
        } else {
            $guards = Permission::pluck('guard_name')->merge(Role::pluck('guard_name'))->unique();
        }

        foreach($guards as $guard){
            $this->info("守卫:{$guard}");
            $roles = Role::whereGuardName($guard)->roderBy('name')->get()->mapWithKeys( function($role){
                return [$role->name, $role->permissions->pluck('name')];
            });

            $permissions = Permission::whereGuardName($guard)->orderBy('name')->pluck('name');
//            ['', '管理员', '报表管理员'];
//            ['add', '✔', '.'];
            $body = $permissions->map(function($permission) use($roles){
                return $roles->map(function($role_permission) use($permission){
                    return $role_permission->contains($permission) ? ' ✔' : ' ·';
                })->prepend($permission);
            });

            $this->table(
                $roles->keys()->prepend('')->toArray(),
                $body->toArray(),
                $style
            );
        }
    }

}