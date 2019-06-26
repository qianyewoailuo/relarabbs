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
}
