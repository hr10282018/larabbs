<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{



    // 只有当话题关联作者的ID等于当前登录用户ID时允许修改帖子
    public function update(User $user, Topic $topic)
    {
      return $user->isAuthorOf($topic);
    }

    // 用户只能删除自己的帖子
    public function destroy(User $user, Topic $topic)
    {
      return $user->isAuthorOf($topic);
    }
}
