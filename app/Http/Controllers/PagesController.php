<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    // relarabbs首页
    public function root()
    {
        // 测试当前用户邮箱是否已验证
        // dd(Auth::user()->hasVerifiedEmail());
        return view('pages.root');
    }

    // 后台访问权限限制页面
    public function permissionDenied()
    {
        // 如果当前用户有权限访问后台，直接跳转访问
        if (config('administrator.permission')()) {
            return redirect(url(config('administrator.uri')), 302);
        }
        // 否则使用视图
        return view('pages.permission_denied');
    }
}
