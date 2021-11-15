<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class SeedCategoriesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $categories = [
          [
              'name'        => '分享',
              'description' => '分享创造，分享发现',
          ],
          [
              'name'        => '教程',
              'description' => '开发技巧、推荐扩展包等',
          ],
          [
              'name'        => '问答',
              'description' => '请保持友善，互帮互助',
          ],
          [
              'name'        => '公告',
              'description' => '站点公告',
          ],
      ];

      DB::table('categories')->insert($categories); //insert()-批量往数据表categories里插入数据
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()  // 在回滚迁移时会被调用
    {
        //
        DB::table('categories')->truncate();// truncate()-清空categories数据表里的所有数据。
    }
}
