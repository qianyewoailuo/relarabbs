<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedLinksData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        // 创建数据表数据
        $data = [
            [
                'title' => 'Laravel 5.8 中文文档',
                'link'  =>  'https://learnku.com/docs/laravel/5.8',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' =>  'Laravel 项目开发规范',
                'link'  =>  'https://learnku.com/docs/laravel-specification/5.5',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' =>  'Laravel 速查表',
                'link'  =>  'https://learnku.com/docs/laravel-cheatsheet/5.8',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' =>  'Laravel 之道',
                'link'  =>  'https://learnku.com/docs/the-laravel-way/5.6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' =>  'Laravel Mix 中文文档',
                'link'  =>  'https://learnku.com/docs/laravel-mix/4.0',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' =>  'Vue 2 入门学习笔记',
                'link'  =>  'https://learnku.com/docs/learn-vue2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // 插入数据
        DB::table('links')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 清空表数据
        DB::table('links')->truncate();
    }
}
