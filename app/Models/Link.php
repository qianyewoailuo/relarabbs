<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Link extends Model
{
    protected $fillable = ['title','link'];

    public $cache_key = 'larabbs_links';
    public $cache_expire = 1400 * 60;

    /**
     * 获取推荐资源数据
     * Cache::remember 是从缓存中获取数据或执行给定的匿名函数并存储结果
     */
    public function getLinkCached()
    {
        return Cache::remember($this->cache_key, $this->cache_expire, function () {
            return $this->all();
        });
    }

}
