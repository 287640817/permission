<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/13 0013
 * Time: 16:01
 */

namespace Zhulei\Permission\Commands;


use Illuminate\Console\Command;
use Zhulei\Permission\Contracts\Role as RoleContract;
use Zhulei\Permission\Contracts\Permission as PermissionContract;

class CreateRole extends Command
{
    protected $signature = 'permission:create-role
                            {name : 角色名}
                            {guard? : 守卫名}
                            {permissions? : 分配给角色的权限列表用|分割}
                            ';
    protected $description = '创建角色';

    public function handle(){
        $roleClass = app(RoleContract::class);
        $role = $roleClass::findOrCreate($this->argument('name'), $this->argument('guard'));
        $role->givePermissionTo($this->makePermissions($this->argument('permissions')));
        $this->info("创建{$this->argument('name')}角色成功!");
    }

    protected function makePermissions(string $permissions = null){
        if(empty($permissions)){
            return;
        }
        $permissionClass = app(PermissionContract::class);
        $permissions  = explode('|', $permissions);
        $models = [];

        foreach($permissions as $permission){
            $models[] = $permissionClass::findOrCreate(trim($permission), $this->argument('guard'));
        }

        return collect($models);

    }
}