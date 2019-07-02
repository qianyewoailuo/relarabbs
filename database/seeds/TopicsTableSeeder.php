<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\User;
use App\Models\Category;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        // 获取所有用户 ID 集合并转为数组
        $user_ids = User::all()->pluck('id')->toArray();
        // 获取所有分类 ID 集合并转为数组
        $category_ids = Category::all()->pluck('id')->toArray();

        // 获取 faker 实例
        $faker = app(Faker\Generator::class);

        // 开始填充
        $topics = factory(Topic::class)
                    ->times(100)
                    ->make()
                    ->each(function ($topic, $index) use ($faker,$user_ids,$category_ids) {
                        // 随机赋值用户字段
                        $topic->user_id = $faker->randomElement($user_ids);
                        // 随机赋值分类字段
                        $topic->category_id = $faker->randomElement($category_ids);
                    });

        $topics = $topics->makeVisible([
            'user_id'
        ])->toArray();
        // 插入数据
        Topic::insert($topics);
    }

}

