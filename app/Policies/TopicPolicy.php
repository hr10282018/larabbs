<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{

    public function destroy(User $user, Topic $topic)
    {
        return true;
    }

    // 只有当话题关联作者的ID等于当前登录用户ID时允许：
    public function update(User $user, Topic $topic)
    {
        return $topic->user_id == $user->id;
    }
}
