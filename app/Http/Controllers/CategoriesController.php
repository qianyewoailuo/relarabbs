<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;
use App\Models\User;

class CategoriesController extends Controller
{
    // 分类展示
    public function show(Category $category, Request $request, User $user)
    {
        // 读取分类 ID 关联的话题,并按每 20 条分页
        $topics = Topic::withOrder($request->order)
            ->where('category_id', $category->id)
            ->with('category', 'user')->paginate(20);

        // 获取活跃用户数据
        $active_users = $user->getActiveUsers();

        // 传参变量话题和分类到模板中
        return view('topics.index', compact('category', 'topics', 'active_users'));
    }
}
