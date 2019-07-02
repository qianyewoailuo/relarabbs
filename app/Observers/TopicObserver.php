<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;

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
        if(! $topic->slug){
            // 如果slug字段无内容则进行翻译生成
            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        }
    }
}
