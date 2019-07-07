<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

trait LastActivedAtHelper
{
    // 缓存相关
    protected $hash_prefix = 'larabbs_last_active_at_';
    protected $field_prefix = 'user_';

    /**
     * 记录用户最后登录时间,并将其记录在redis哈希表中
     */
    public function recordLastActivedAt()
    {
        // 获取今天的日期
        $date = Carbon::now()->toDateString();

        // Redis 哈希表命名 如: larabbs_last_actived_at_2019-7-7
        $hash = $this->hash_prefix . $date;

        // 字段名称 如: user_1
        $field = $this->field_prefix . $this->id;

        // dd(Redis::hGetAll($hash));  // 测试获取的哈希表数据

        $now = Carbon::now()->toDateTimeString();
        // 写入 redis 字段已存在会被更新
        Redis::hSet($hash, $field, $now);
    }

    /**
     * 同步redis哈希表中的用户最后登录时间到数据库
     */
    public function syncUserActivedAt()
    {
        // 获取昨天的日期 格式如: 2019-7-6
        $yearsterday = Carbon::yesterday()->toDateString();
        // $yearsterday = Carbon::now()->toDateString();

        // 哈希表名
        $hash = $this->hash_prefix . $yearsterday;

        // 获取哈希表中的数据
        $dates = Redis::hGetAll($hash);

        // 遍历数据并同步到数据库中
        foreach ($dates as $key => $value) {
            //将字段值(例如 user_1)转换为ID值(例如 1)
            $user_id = str_replace($this->field_prefix,'',$key);

            // 只有当用户存在时才更新到数据库中
            if($user = $this->find($user_id)){
                $user->last_actived_at = $value;
                $user->save();
            }
        }

        // 同步完成即可将此哈希表删除
        Redis::del($hash);
    }

    /**
     * 属性访问器获取用户最后登录时间
     */
    public function getLastActivedAtAttribute($value)
    {
        // 获取今天的时间
        $today = Carbon::now()->toDateString();
        // 获取哈希表名
        $hash = $this->hash_prefix.$today;
        // 获取字段名
        $field = $this->field_prefix.$this->id;

        // 优先选择 Redis 的数据，否则使用数据库中
        $datetime = Redis::hGet($hash,$field)?:$value;

        if($datetime){
            // 如果数据可以获取返回相应的 Carbon 实体
            return new Carbon($datetime);
        }else{
            // 否则使用用户创建时间
            return $this->created_at;
        }
    }
}
