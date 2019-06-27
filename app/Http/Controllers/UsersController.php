<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;

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
    public function update(User $user,UserRequest $request)
    {
        $user->update($request->all());
        return redirect()->route('users.show',$user)
                        ->with('success','个人资料更新成功');
    }
}
