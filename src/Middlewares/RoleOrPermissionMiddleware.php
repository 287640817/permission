<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 15:32
 */

namespace Zhulei\Permission\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Zhulei\Permission\Exceptions\UnauthorizedException;

class RoleOrPermissionMiddleware
{
    public function handle($request, Closure $next, $roleOrPermission){
        if(Auth::guest()){
            throw UnauthorizedException::notLoggedIn();
        }

        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);
        if (! Auth::user()->hasAnyRole($rolesOrPermissions) && ! Auth::user()->hasAnyPermission($rolesOrPermissions)) {
            throw UnauthorizedException::forRolesOrPermissions($rolesOrPermissions);
        }
        return $next($request);

    }
}