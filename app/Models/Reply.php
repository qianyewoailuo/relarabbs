<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    /**
     * 关联关系
     * topic 一个回复属于一个话题
     */

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * 关联关系
     * user 一个回复属于一个用户
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
