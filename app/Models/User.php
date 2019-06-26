<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
// 邮箱验证 trait, 拥有下面3个方法:
// hasVerifiedEmail() 检测用户 Email 是否已认证；
// markEmailAsVerified() 将用户标示为已认证
// sendEmailVerificationNotification() 发送 Email 认证的消息通知，触发邮件的发送
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
// PHP 的接口类，继承此类将确保 User 遵守契约，拥有上面提到的三个方法
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use Notifiable, MustVerifyEmailTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
