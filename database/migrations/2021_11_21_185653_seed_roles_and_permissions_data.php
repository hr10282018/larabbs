<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class SeedRolesAndPermissionsData extends Migration
{

    public function up()
    {
      // 需清除缓存，否则会报错
      app(Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

      // 先创建权限
      Permission::create(['name' => 'manage_contents']);  // 管理内容(管理员、站长)
      Permission::create(['name' => 'manage_users']);     // 管理用户(站长)
      Permission::create(['name' => 'edit_settings']);    // 编辑设置(站长)

      // 创建站长角色，并赋予权限，站长有全部权限
      $founder = Role::create(['name' => 'Founder']);
      $founder->givePermissionTo('manage_contents');
      $founder->givePermissionTo('manage_users');
      $founder->givePermissionTo('edit_settings');

      // 创建管理员角色，并赋予管理内容权限
      $maintainer = Role::create(['name' => 'Maintainer']);
      $maintainer->givePermissionTo('manage_contents');

    }


    public function down()
    {
      // 需清除缓存，否则会报错
      app(Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

      // 清空所有数据表数据
      $tableNames = config('permission.table_names');

      Model::unguard();
      DB::table($tableNames['role_has_permissions'])->delete();
      DB::table($tableNames['model_has_roles'])->delete();
      DB::table($tableNames['model_has_permissions'])->delete();
      DB::table($tableNames['roles'])->delete();
      DB::table($tableNames['permissions'])->delete();
      Model::reguard();

    }
}
