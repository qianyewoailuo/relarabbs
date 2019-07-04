<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\Topic;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        // 获取所有用户 ID 数组
        $user_ids = App\Models\User::all()->pluck('id')->toArray();
        // 获取所有话题 ID 数组
        $topic_ids = Topic::all()->pluck('id')->toArray();

        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        $replys = factory(Reply::class)
                    ->times(100)
                    ->make()
                    ->each(function ($reply, $index)
                        use ($user_ids,$topic_ids,$faker)
                    {
                        // 从用户中随机取出一个
                        $reply->user_id = $faker->randomElement($user_ids);

                        $reply->topic_id = $faker->randomElement($topic_ids);

                    });

        // 插入数据
        Reply::insert($replys->toArray());
    }

}

