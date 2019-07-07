<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Handlers\ImageUploadHandler;
use App\Models\User;
use App\Models\Link;

class TopicsController extends Controller
{
    public function __construct()
    {
        // 限制未登录用户对 TOPICS 路由的访问权限
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化返回的数据,默认上传失败
        $data = [
            'success'   => false,
            'msg'       => '上传失败',
            'file_path' => ''
        ];

        // 判断是否有上传文件,并赋值给$file
        if ($file = $request->upload_file) {
            // 使用自定义的上传类保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', Auth::id(), 1024);

            // 如果保存成功
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['success'] = true;
                $data['msg']    = '上传成功';
            }
        }

        // 在 Laravel 的控制器方法中如果直接返回数组数据将会被转换为JSON
        return $data;
    }

    public function index(Request $request,User $user,Link $link)
    {
        $topics = Topic::query()->withOrder($request->order)
            ->with('category', 'user','replies')->paginate(20);

        // 获取活跃用户数据
        $active_users = $user->getActiveUsers();

        // 获取推荐资源
        $links = $link->getLinkCached();

        return view('topics.index', compact('topics','active_users','links'));
    }

    public function show(Topic $topic, Request $request)
    {
        // 如果有slug参数则URL强制跳转到slug参数路由
        if (!empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }

        return view('topics.show', compact('topic'));
    }

    public function create(Topic $topic)
    {
        // 获取分类数据
        $categories = Category::all();

        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function store(TopicRequest $request, Topic $topic)
    {
        // 不能使用create创建 因为user_id必需但$fillable中限制用户自行写入
        // $topic = Topic::create($request->all());

        // 使用 save 方法保存
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();

        // return redirect()->route('topics.show', $topic->id)->with('success', '帖子创建成功');
        // slug优化跳转
        return redirect()->to($topic->link())->with('success', '话题创建成功');
    }

    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        // return redirect()->route('topics.show', $topic->id)->with('success', '话题编辑成功');
        // slug优化跳转
        return redirect()->to($topic->link())->with('success', '话题编辑成功');
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return redirect()->route('topics.index')->with('success', '话题删除成功');
    }
}
