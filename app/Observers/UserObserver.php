<?php

namespace App\Observers;

use App\Models\User;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class UserObserver
{
    public  function saving(User $user)
    {
        // 默认头像
        if(empty($user->avatar)){
            $user->avatar = "https://s2.ax1x.com/2019/05/05/EwxTl6.png";
        }
    }

    // public function deleted(User $user)
    // {
    //     \DB::table('topics')->where('user_id',$user->id)->delete();
    //     \DB::table('replies')->where('user_id',$user->id)->delete();
    // }
}
