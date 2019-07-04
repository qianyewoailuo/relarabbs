<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic)
    {
        // HTMLPurifier 白名单XXS过滤
        $topic->body = clean($topic->body, 'user_topic_body');

        // make_excerpt 是自定义的辅助函数.用来生成摘要
        $topic->excerpt = make_excerpt($topic->body);

        // slug SEO优化字段生成
        // if(! $topic->slug){
        //     // 如果slug字段无内容则进行翻译生成 - 非队列
        //     $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        // }
    }

    public function saved(Topic $topic)
    {
        if (!$topic->slug) {
            // 使用队列进行 slug 翻译
            dispatch(new TranslateSlug($topic));
        }
    }

    /**
     * 话题删除后相应回复一并删除
     */
    public function deleted(Topic $topic)
    {
        // 使用 DB 类避免删除回复时再次触发 Eloquent 事件使得 ReplyObserver 中执行 deleted 方法出错
        \DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}
