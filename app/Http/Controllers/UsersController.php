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
        return view('users.show',compact('user'));
    }

    // 个人资料编辑展示
    public function edit(User $user)
    {
        return view('users.edit',compact('user'));
    }

    // 个人资料编辑更新
    public function update(User $user,UserRequest $request,ImageUploadHandler $imageUploadHandler)
    {
        // 测试获取的上传头像信息
        // dd($request->avatar);
        // $user->update($request->all());

        // 获取数据
        // $data = $request->all();
        $data = $request->except('avatar');
        // dd($data);

        if ($request->file('avatar')){
            $result = $imageUploadHandler->save($request->file('avatar'),'avatar',$user->id);
            if($result){
                $data['avatar'] = $result['path'];
            }else{
                return back()->with('danger','图片格式不正确');
                // $data['avatar'] = '';
            }
        }

        // 更新数据
        $user->update($data);
        return redirect()->route('users.show',$user)
                        ->with('success','个人资料更新成功');
    }
}
