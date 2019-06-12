<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTables extends Migration{

    public function up() {
        $table_names = config('permission.tables_names');
        $column_names = config('permission.column_names');
        //创建权限表
        Schema::create($table_names['permissions'], function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->comment('权限名');
            $table->string('display_name', 255)->comment('权限显示名');
            $table->string('tag', '255')->comment('权限分组标签');
            $table->string('guard_name');
            $table->string('description')->comment('权限描述');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `{$table_names['permissions']}` comment '权限表'");

        //创建角色表
        Schema::create($table_names['roles'], function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->comment('角色名');
            $table->string('display_name')->comment('角色显示名');
            $table->string('description')->comment('角色描述');
            $table->string('guard_name');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `{$table_names['roles']}` comment '角色表' ");

        Schema::create($table_names['model_has_permissions'], function(Blueprint $table) use($table_names, $column_names){
            $table->unsignedInteger('permission_id');
            $table->string();
        });
    }

    public function down(){

    }


}
