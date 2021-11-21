<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // 生成数据集合-10个假用户
      $users=factory(User::class)->times(10)->create();

      // 单独处理第一个用户
      $user=User::find(1);
      $user->name = 'Sakura';
      $user->email = '1902422119@qq.com';
      $user->avatar = 'http://larabbs.test/images/sakura.png';
      $user->save();

       // 初始化用户角色，将1号用户指派为『站长』
      $user->assignRole('Founder'); // assignRole()-该方法在HasRoles中定义，我们已在User模型中加载了

      // 将 2 号用户指派为『管理员』
      $user = User::find(2);
      $user->assignRole('Maintainer');
    }
}
