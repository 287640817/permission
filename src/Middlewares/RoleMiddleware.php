<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 15:19
 */

namespace Zhulei\Permission\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Zhulei\Permission\Exceptions\UnauthorizedException;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role){
        if(Auth::guest()){
            throw UnauthorizedException::forRoles($role);
        }
        $roles = is_array($role) ? $role : explode('|', $role);

        if(! Auth::user()->hasAnyRole($role)){
            throw UnauthorizedException::forRoles($roles);
        }
        return $next($request);
    }
}