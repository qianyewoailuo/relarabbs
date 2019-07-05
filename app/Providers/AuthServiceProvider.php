<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     * 手动指定 授权策略
     *
     * @var array
     */
    protected $policies = [
		 \App\Models\Reply::class => \App\Policies\ReplyPolicy::class,
		 \App\Models\Topic::class => \App\Policies\TopicPolicy::class,
        // 'App\Model' => 'App\Policies\ModelPolicy',
        \App\Models\User::class => \App\Policies\UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     * 自动发现 授权策略 5.8之后的版本才有 暂时不考虑
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 修改策略自动发现的逻辑
        // Gate::guessPolicyNamesUsing(function ($modelClass) {
        //     // 动态返回模型对应的策略名称，如：// 'App\Model\User' => 'App\Policies\UserPolicy',
        //     return 'App\Policies\\' . class_basename($modelClass) . 'Policy';
        // });

        \Horizon::auth(function ($request) {
            // 是否是站长
            return \Auth::user()->hasRole('Founder');
        });
    }
}
