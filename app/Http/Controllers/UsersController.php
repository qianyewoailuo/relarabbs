<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    // 个人主页展示
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // 个人资料编辑展示
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // 个人资料编辑更新 version 2 : 使用表单请求类型过滤时
    public function update(User $user, UserRequest $request, ImageUploadHandler $imageUploadHandler)
    {
        // 获取表单提交的数据
        $data = $request->all();

        // 处理上传文件
        if ($request->file('avatar')) {
            $result = $imageUploadHandler->save($request->file('avatar'), 'avatar', $user->id);
            if ($result) {
                $data['avatar'] = $result['path'];
            } else {
                // 健壮性考虑,如果上传失败取回原头像数据
                $data['avatar'] = $user->avatar;
            }
        }

        // 更新用户数据并返回响应
        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功');
    }

    // 个人资料编辑更新 - version 1: 没有使用 表单请求类过滤类型时
    // public function update(User $user,UserRequest $request,ImageUploadHandler $imageUploadHandler)
    // {
    //     // 测试获取的上传头像信息
    //     // dd($request->avatar);
    //     // $user->update($request->all());

    //     // 获取数据
    //     // $data = $request->all();
    //     $data = $request->except('avatar');
    //     // dd($data);

    //     if ($request->file('avatar')){
    //         $result = $imageUploadHandler->save($request->file('avatar'),'avatar',$user->id);
    //         if($result){
    //             $data['avatar'] = $result['path'];
    //         }else{
    //             return back()->with('danger','图片格式不正确');
    //             // $data['avatar'] = '';
    //         }
    //     }

    //     // 更新数据
    //     $user->update($data);
    //     return redirect()->route('users.show',$user)
    //                     ->with('success','个人资料更新成功');
    // }
}
