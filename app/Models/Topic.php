<?php

namespace App\Models;

use App\Models\Category;
use App\Models\User;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'user_id', 'category_id', 'reply_count', 'view_count', 'last_reply_user_id', 'order', 'excerpt', 'slug'];

    // 模型关联关系
    // 有以下关联设定，就能很快的通过 $topic->category、$topic->user 来获取到话题对应的分类和作者。
    public function category()
    {
        // 一个话题属于一种分类
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        // 一个话题属于一个用户
        return $this->belongsTo(User::class);
    }
}
