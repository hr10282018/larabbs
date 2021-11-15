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
      $this->call(UsersTableSeeder::class); //调用生成用户假数据
      $this->call(TopicsTableSeeder::class);  //调用生成话题假数据
    }
}
