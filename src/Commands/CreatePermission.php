<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/13 0013
 * Time: 15:51
 */

namespace Zhulei\Permission\Commands;


use Illuminate\Console\Command;
use Zhulei\Permission\Contracts\Permission as PermissionContract;

class CreatePermission extends Command
{
    protected $signature = 'permission:create-permission
                            {name : 权限名}
                            {guard? : 守卫名}
                            ';
    protected $description = '创建权限';

    public function handle(){
        $permissionClass = app(PermissionContract::class);
        $permission = $permissionClass::findOrCreate($this->arguments('name'), $this->arguments('guard'));
        $this->info("权限{$permission->name}创建成功！");
    }

}