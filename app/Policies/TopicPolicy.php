<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    public function update(User $currentUser, Topic $topic)
    {
        return $topic->user_id == $currentUser->id;
    }

    public function destroy(User $user, Topic $topic)
    {
        return true;
    }
}
