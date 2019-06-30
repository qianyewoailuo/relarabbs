<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

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

$factory->define(User::class, function (Faker $faker) {
    $faker_time = $faker->date.''.$faker->time;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => Hash::make('luo12345'), // 或者使用 bcrypt() 哈希密码辅助函数
        'remember_token' => Str::random(10),
        'introduction' => $faker->sentence(), // 小段落文本
        'created_at' => $faker_time,
        'updated_at' => $faker_time,
    ];
});
