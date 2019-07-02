<?php

namespace App\Models;

use App\Models\Category;
use App\Models\User;

class Topic extends Model
{
    // protected $fillable = ['title', 'body', 'user_id', 'category_id', 'reply_count', 'view_count', 'last_reply_user_id', 'order', 'excerpt', 'slug'];

    protected $fillable = [
        'title','body','category_id','excerpt','slug'
    ];

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

    // 话题列表排序
    /**
     * 本地作用域
     * scope + Name 是 本地作用域 是应用
     * 本地作用域允许我们定义通用的约束集合以便在应用中复用。
     * 定义这样的作用域，只需在对应 Eloquent 模型方法前加上一个 scope 前缀，
     * 作用域总是返回当前模型的查询构建器 ,一般可以使用 $query 表示
     * 一旦定义了作用域，则可在查询模型时调用。但注意在调用时不需要加上 scope 前缀。
     */
    public function scopeWithOrder($query,$order)
    {
        // 使用 case 针对不同排序将不同数据进行逻辑处理
        switch ($order) {
            // 最新发布排序
            case 'recent':
                $query->recent();   // 调用本地作用域的方法
                break;
            // 最新回复排序
            default:
                $query->recentReplied();    // 调用本地作用域的方法
                break;
        }

        // 增加预防 N+1 预加载 (但原来已经在控制器中写了这里就展示忽略了)
        // return $query->with('category','user');
    }

    public function scopeRecent($query)
    {
        // 最新发布排序的本地作用域方法
        return $query->orderBy('created_at','desc');
    }

    public function scopeRecentReplied($query)
    {
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at','desc');
    }

    /**
     * topics.show slug链接生成
     */
    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }
}
