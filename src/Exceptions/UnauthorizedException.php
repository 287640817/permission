<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14 0014
 * Time: 14:46
 */

namespace Zhulei\Permission\Exceptions;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{
    private $requiredRoles = [];
    private $requiredPermissions = [];

    public static function forRoles(array $roles): self
    {
        $message = '用户角色不对.';
        if (config('permission.display_permission_in_exception')) {
            $permStr = implode(', ', $roles);
            $message = '用户角色不对，角色必须是 '.$permStr;
        }
        $exception = new static(403, $message, null, []);
        $exception->requiredRoles = $roles;
        return $exception;
    }

    public static function forPermissions(array $permissions): self
    {
        $message = '用户没有权限.';
        if (config('permission.display_permission_in_exception')) {
            $permStr = implode(', ', $permissions);
            $message = '用户没有权限，权限必须为 '.$permStr;
        }
        $exception = new static(403, $message, null, []);
        $exception->requiredPermissions = $permissions;
        return $exception;
    }

    public static function forRolesOrPermissions(array $rolesOrPermissions): self
    {
        $message = '用户没有权限.';
        if (config('permission.display_permission_in_exception') && config('permission.display_role_in_exception')) {
            $permStr = implode(', ', $rolesOrPermissions);
            $message = '用户没有权限，权限必须为 '.$permStr;
        }
        $exception = new static(403, $message, null, []);
        $exception->requiredPermissions = $rolesOrPermissions;
        return $exception;
    }

    public static function notLoggedIn(string $message = '用户未登陆.'): self
    {
        return new static(403, $message, null, []);
    }

    public function getRequiredRoles(): array
    {
        return $this->requiredRoles;
    }

    public function getRequiredPermissions(): array
    {
        return $this->requiredPermissions;
    }
}