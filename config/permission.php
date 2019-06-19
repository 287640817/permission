<?php

return [
    'models' => [
        'permission' => Zhulei\Permission\Models\Permission::class,
        'role' => Zhulei\Permission\Models\Role::class,
    ],
    'tables_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_roles' => 'manage_has_roles',//用户和角色对应关系
        'role_has_permissions' => 'role_has_permissions',//角色和权限对应关系
        'model_has_permissions' => 'manage_has_permissions',//
    ],
    'column_names' => [
        'model_morph_key' => 'manage_id'
    ],
    'display_permission_in_exception' => false,
    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'zhulei.permission.cache',
        'model_key' => 'name',
        'store' => 'default',//使用存储驱动是什么 是cache.php中的键
    ]
];