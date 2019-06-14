<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 14:00
 */

namespace Zhulei\Permission\Traits;


use Zhulei\Permission\PermissionRegistrar;

trait RefreshesPermissionCache
{
    public static function bootRefreshesPermissionCache(){
        static::saved(function(){
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        });

        static::deleted(function(){
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        });
    }
}