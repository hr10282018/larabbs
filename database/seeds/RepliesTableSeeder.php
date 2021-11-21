<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;

class RepliesTableSeeder extends Seeder
{
    public function run()
    {
      $replies =factory(Reply::class)->times(300)->create(); // 1000条假数据
    }

}

