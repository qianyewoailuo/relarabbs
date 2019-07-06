<?php

namespace App\Models\Traits;

use App\Models\Topic;
use App\Models\Reply;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

trait ActiveUserHelper
{

    // 用于存放临时用户数据的属性
    protected $users = [];

    // 权重及判定等配置信息
    protected $topic_weight = 4;    // 话题权重
    protected $reply_weight = 1;    // 回复权重
    protected $pass_days    = 7;    // 多少天内发表过内容
    protected $user_number  = 6;    // 记录显示的活跃用户数

    // 缓存相关配置
    protected $cache_key = 'larabbs_active_users';  // 缓存键
    protected $cache_exprie_in_seconds = 65 * 60;   // 缓存时间

    /**
     * 获取活跃用户
     */
    public function getActiveUsers()
    {
        // 先尝试从缓存中取出相应数据,如果能获取得到则直接使用
        // 否则运行匿名函数中的代码取出活跃用户数据,返回的同时进行缓存
        return Cache::remember($this->cache_key, $this->cache_exprie_in_seconds, function () {
            return $this->calculateActiveUsers();
        });
    }

    /**
     * 计算并缓存活跃用户数据
     */
    public function calculateAndCacheActiveUsers()
    {
        // 取得活跃用户列表
        $active_users = $this->calculateActiveUsers();
        // 并缓存活跃用户数据
        $this->cacheActiveUsers($active_users);
    }

    /**
     * 计算活跃用户的方法
     */
    private function calculateActiveUsers()
    {
        // 计算话题权重
        $this->calculateTopicScore();
        // 计算回复权重
        $this->calculateReplyScore();

        // 数组按照得分排序
        $users = Arr::sort($this->users,function($user){
            return $user['score'];
        });

        // 高分排前进行数组倒序, 并且保持数组的 KEY 不变
        $users = array_reverse($users,true);

        // 获取需要的活跃用户数量, 并且保持数组的 KEY 不变
        $users = array_slice($users,0,$this->user_number,true);

        // 新建空集合
        $active_users = collect();

        foreach ($users as $key => $value) {
            $user = $this->find($key);
            if($user){
                // 相应 key 能找到相对应的用户时将此对象放入集合末尾
                $active_users->push($user);
            }
        }

        return $active_users;
    }

    /**
     * 计算话题权重得分
     */
    private function calculateTopicScore()
    {
        // 从话题数据表中取出限定时间范围内发表过话题的用户
        // 并且同时取出用户此段时间内发布话题的数量
        $topic_users = Topic::query()->select(DB::raw('user_id,count(*) as topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();

        // 根据话题数量计算得分
        foreach ($topic_users as $key => $value) {
            $this->users[$value->user_id]['score'] = $value->topic_count * $this->topic_weight;
        }
    }

    /**
     * 计算回复权重得分
     */
    private function calculateReplyScore()
    {
        // 从回复数据表中取出限定时间范围内发表过回复的用户
        // 并且同时取出用户此段时间类发布回复的数量
        $reply_users = Reply::query()->select(DB::raw('user_id,count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();

        // 根据回复数量计算得分
        foreach ($reply_users as $key => $value) {
            if (isset($this->users[$value->user_id])) {
                $this->users[$value->user_id]['score'] += $value->reply_count * $this->reply_weight;
            } else {
                $this->users[$value->user_id]['score'] = $value->reply_count * $this->reply_weight;
            }
        }
    }

    /**
     * 缓存活跃用户的方法
     */
    private function cacheActiveUsers($active_users)
    {
        // 将数据放入缓存中
        Cache::put($this->cache_key, $active_users, $this->cache_exprie_in_seconds);
    }
}
