<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/24 0024
 * Time: 10:21
 */

namespace Zhulei\Permission\Database;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Zhulei\Permission\Contracts\Role;


class PermissionDbSeeder extends Seeder
{
    public function run(){
        // 重置角色和权限
        app()[\Zhulei\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $role = Role::create(
            [
                'name' => '超级管理员',
                'display_name' => "超级管理员",
                'description' => '拥有所有权限',
            ]
        );
        //name就是路由的名字
        $permissions = [
            //角色列表
            [
                'name' => 'manage.role.index',
                'display_name' => '角色列表',
                'tag' => '角色管理',
                'description' => '角色列表显示'
            ],
            //添加角色
            [
                'name' => 'manage.role.create',
                'display_name' => '添加角色',
                'tag' => '角色管理',
                'description' => '添加角色页面'
            ],
            //添加角色保存
            [
                'name' => 'manage.role.store',
                'display_name' => '添加角色保存',
                'tag' => '角色管理',
                'description' => '添加角色保存'
            ],
            //角色详情
            [
                'name' => 'manage.role.show',
                'display_name' => '角色详情',
                'tag' => '角色管理',
                'description' => '角色详情权限'
            ],
            //编辑角色
            [
                'name' => 'manage.role.edit',
                'display_name' => '编辑角色',
                'tag' => '角色管理',
                'description' => '编辑角色'
            ],
            //编辑角色保存
            [
                'name' => 'manage.role.update',
                'display_name' => '编辑角色保存',
                'tag' => '角色管理',
                'description' => '编辑角色保存'
            ],
            //删除角色
            [
                'name' => 'manage.role.destroy',
                'display_name' => '删除角色',
                'tag' => '角色管理',
                'description' => '删除角色'
            ],


            //权限列表
            [
                'name' => 'manage.permission.index',
                'display_name' => '权限列表',
                'tag' => '权限管理',
                'description' => '权限列表显示'
            ],
            //添加权限
            [
                'name' => 'manage.permission.create',
                'display_name' => '添加权限',
                'tag' => '权限管理',
                'description' => '添加权限页面'
            ],
            //添加权限保存
            [
                'name' => 'manage.permission.store',
                'display_name' => '添加权限保存',
                'tag' => '权限管理',
                'description' => '添加权限保存'
            ],
            //权限详情
            [
                'name' => 'manage.permission.show',
                'display_name' => '权限详情',
                'tag' => '权限管理',
                'description' => '权限详情权限'
            ],
            //编辑权限
            [
                'name' => 'manage.permission.edit',
                'display_name' => '编辑权限',
                'tag' => '权限管理',
                'description' => '编辑权限'
            ],
            //编辑权限保存
            [
                'name' => 'manage.permission.update',
                'display_name' => '编辑权限保存',
                'tag' => '权限管理',
                'description' => '编辑权限保存'
            ],
            //删除权限
            [
                'name' => 'manage.permission.destroy',
                'display_name' => '删除权限',
                'tag' => '权限管理',
                'description' => '删除权限'
            ],
        ];
        $permissions =  DB::table(app(Role::class)->getTable())->insert($permissions);
        $role->givePermissionTo($permissions);
    }
}