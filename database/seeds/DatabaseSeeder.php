<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $this->call(UsersTableSeeder::class);   // 调用生成用户假数据

      $this->call(TopicsTableSeeder::class);  // 调用生成话题假数据

      $this->call(RepliesTableSeeder::class); // 调用生成评论假数据

      $this->call(LinksTableSeeder::class);   // 调用生成资源推荐假数据
    }
}
