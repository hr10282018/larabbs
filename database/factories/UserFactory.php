<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
// 头像假数据
$avatars = [
  'https://cdn.learnku.com/uploads/images/201710/14/1/s5ehp11z6s.png',
  'https://cdn.learnku.com/uploads/images/201710/14/1/Lhd1SHqu86.png',
  'https://cdn.learnku.com/uploads/images/201710/14/1/LOnMrqbHJn.png',
  'https://cdn.learnku.com/uploads/images/201710/14/1/xAuDMxteQy.png',
  'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png',
  'https://cdn.learnku.com/uploads/images/201710/14/1/NDnzMutoxX.png',
];

$factory->define(User::class, function (Faker $faker) use ($avatars) {  //use-使用假头像
    $date_time = $faker->date . ' ' . $faker->time; //

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'avatar' => $faker->randomElement($avatars),  //数组随机取元素
        'introduction' => $faker->sentence(), //随机生成『小段落』文本
        'created_at' => $date_time,
        'updated_at' => $date_time,
    ];
});
