<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/13 0013
 * Time: 15:46
 */

namespace Zhulei\Permission\Commands;

use Illuminate\Console\Command;
use Zhulei\Permission\PermissionRegistrar;


class CacheReset extends Command
{
    protected $signature = 'permission:cache-reset';
    protected $description = '删除权限缓存';

    public function handle(){
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->info("清除权限成功！");
    }
}