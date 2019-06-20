<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 15:09
 */

namespace Zhulei\Permission\Middlewares;

use Closure;
use Zhulei\Permission\Exceptions\UnauthorizedException;

class PermissionMiddleware
{

    public function handle($request, Closure $next, $permission){
        if(app('auth')->guest()){
            throw UnauthorizedException::notLoggedIn("用户未登陆");
        }
        $permissions = is_array($permission) ? $permission: explode('|', $permission);
        foreach($permissions as $permission){
            if(app('auth')->user()->can($permission)) {
                return $next($request);
            }
        }
        throw UnauthorizedException::forPermissions($permission);
    }
}