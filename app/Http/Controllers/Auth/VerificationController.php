<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    // 上面的 trait 可以重写方法以达到自己想要的业务逻辑(例如已验证邮箱后的提示信息闪存)
    // 这次是使用事件与监听器的方法进行闪存提醒信息

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 登录用户认证
        $this->middleware('auth');
        // 路由签名认证
        $this->middleware('signed')->only('verify');
        // 验证与重新发送邮件频率限制
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
