<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{

    public function destroy(User $user, Reply $reply)
    {
        // 代码重用与易读不使用
        // return $reply->user_id == $user->id || $reply->topic->user_id == $user->id;
        // user模型中新增方法利于代码重用
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
