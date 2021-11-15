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
      $user->avatar = 'https://cdn.learnku.com/images/路飞.png';
      $user->save();

    }
}
