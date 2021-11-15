<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
      factory(Topic::class)->times(100)->create();  //100条话题数据

        /* $topics = factory(Topic::class)->times(50)->make()->each(function ($topic, $index) {
            if ($index == 0) {
                // $topic->field = 'value';
            }
        });

        Topic::insert($topics->toArray());
        */
    }

}

